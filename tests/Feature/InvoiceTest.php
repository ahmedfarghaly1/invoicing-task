<?php

namespace Tests\Unit;

use App\Models\InvoiceItem;
use App\Services\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\User;
use App\Models\Session;
use App\Models\Invoice;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a customer with users, sessions, invoices, and invoice items.
     */
    public function test_create_customer_with_users_sessions_invoices_and_items(): void
    {
        $customer = Customer::factory()
            ->has(
                User::factory()
                    ->count(3) // Create 3 users
                    ->has(
                        Session::factory()
                            ->count(2) // Each user has 2 sessions
                    )
            )
            ->has(
                Invoice::factory()
                    ->count(2)
            )
            ->create();


        $this->assertDatabaseHas('customers', ['id' => $customer->id]);

        $this->assertCount(3, $customer->users);

        foreach ($customer->users as $user) {
            $this->assertCount(2, $user->sessions);
        }

        $this->assertCount(2, $customer->invoices);

        foreach ($customer->invoices as $invoice) {
            $this->assertCount(5, $invoice->items);
        }

        $invoice = $customer->invoices->first();
        $calculatedTotal = $invoice->items->sum('event_price');
        $this->assertEquals($calculatedTotal, $invoice->total_price);
    }

    public function test_invoice_generation_with_existing_invoices_and_items(): void
    {
        $customer = Customer::factory()->create();

        // Pre-existing Invoices
        $invoice1 = Invoice::create([
            'customer_id' => $customer->id,
            'start_date' => '2020-10-01',
            'end_date' => '2020-10-31',
            'total_price' => 100,
        ]);

        $invoice2 = Invoice::create([
            'customer_id' => $customer->id,
            'start_date' => '2020-12-01',
            'end_date' => '2020-12-31',
            'total_price' => 200,
        ]);

        // Pre-existing Invoice Items
        $userA = User::factory()->create(['customer_id' => $customer->id, 'registered_at' => '2020-12-01']);
        $userD = User::factory()->create(['customer_id' => $customer->id, 'registered_at' => '2020-09-01']);

        InvoiceItem::create([
            'invoice_id' => $invoice1->id,
            'user_id' => $userD->id,
            'event_type' => InvoiceService::ACTIVATION,
            'event_price' => 100,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice2->id,
            'user_id' => $userA->id,
            'event_type' => InvoiceService::REGISTRATION,
            'event_price' => 50,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice2->id,
            'user_id' => $userD->id,
            'event_type' => InvoiceService::APPOINTMENT,
            'event_price' => 200,
        ]);

        // Create other users and sessions
        $userB = User::factory()->create(['customer_id' => $customer->id, 'registered_at' => '2020-12-15']);
        $userC = User::factory()->create(['customer_id' => $customer->id, 'registered_at' => '2021-01-01']);

        Session::factory()->create(['user_id' => $userA->id, 'activated_at' => '2021-01-15']);
        Session::factory()->create(['user_id' => $userA->id, 'activated_at' => '2021-01-18']);

        Session::factory()->create(['user_id' => $userB->id, 'appointment_at' => '2021-01-15']);

        Session::factory()->create(['user_id' => $userC->id, 'activated_at' => '2021-01-10']);

        Session::factory()->create(['user_id' => $userD->id, 'activated_at' => '2021-01-12']);
        Session::factory()->create(['user_id' => $userD->id, 'appointment_at' => '2021-01-22']);

        // Generate the invoice for the specified period
        $invoiceService = new InvoiceService();
        $invoiceData = $invoiceService->generateInvoice([
            'start_date' => '2021-01-01',
            'end_date' => '2021-02-01',
            'customer_id' => $customer->id,
        ]);

        $invoice = Invoice::find($invoiceData['data']['invoice_id']);

        // Assertions
        $this->assertNotNull($invoice);
        $this->assertEquals('2021-01-01', $invoice->start_date);
        $this->assertEquals('2021-02-01', $invoice->end_date);

        // Assert total price
        $this->assertEquals(350, $invoice->total_price);
    }


    public function test_show_invoice_details()
    {
        // Create customer and invoice data
        $customer = Customer::factory()->create(['name' => 'Test Customer']);

        $invoice = Invoice::factory()->create([
            'customer_id' => $customer->id,
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-31',
            'total_price' => 350,
        ]);

        $userA = User::factory()->create(['customer_id' => $customer->id, 'email' => 'userA@example.com']);
        $userB = User::factory()->create(['customer_id' => $customer->id, 'email' => 'userB@example.com']);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'user_id' => $userA->id,
            'event_type' => 'registration',
            'event_price' => 50,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'user_id' => $userB->id,
            'event_type' => 'appointment',
            'event_price' => 200,
        ]);

        $this->assertDatabaseHas('invoices', ['id' => $invoice->id]);

        $response = $this->getJson(route("invoices.show",  $invoice));

        $response->assertStatus(200);

        $response->assertJson(
            [
                'data' => [
                    'invoice_id' => $invoice->id,
                    'customer' => $customer->name,
                    'start_date' => '2021-01-01',
                    'end_date' => '2021-01-31',
                    'total_price' => 350,
                ]
            ]
        );
    }





}
