<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'start_date' => $this->faker->dateTimeBetween('-2 months', '-1 month')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'total_price' => 0, // Will be calculated dynamically later
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Invoice $invoice) {
            $items = \App\Models\InvoiceItem::factory(5)->create([
                'invoice_id' => $invoice->id,
            ]);

            $invoice->update([
                'total_price' => $items->sum('event_price'),
            ]);
        });
    }
}
