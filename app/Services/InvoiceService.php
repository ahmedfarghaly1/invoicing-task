<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Session;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    public const REGISTRATION = 'registration';
    public const ACTIVATION = 'activation';
    public const APPOINTMENT = 'appointment';

    /**
     * Generate an invoice for a customer.
     */
    public function generateInvoice(array $validatedData): array
    {
        $startDate = $validatedData['start_date'];
        $endDate = $validatedData['end_date'];
        $customerId = $validatedData['customer_id'];

        DB::beginTransaction();

        try {
            $users = User::where('customer_id', $customerId)->get();
            $totalPrice = 0;
            $invoiceItems = [];

            foreach ($users as $user) {
                $currentEventPrices = [
                    self::REGISTRATION => $this->calculateRegistrationPrice($user, $startDate, $endDate),
                    self::ACTIVATION => $this->calculateActivationPrice($user, $startDate, $endDate),
                    self::APPOINTMENT => $this->calculateAppointmentPrice($user, $startDate, $endDate),
                ];

                $currentHighestPrice = max($currentEventPrices);
                $currentEventType = array_search($currentHighestPrice, $currentEventPrices);

                $eventPaidInPastPeriod = InvoiceItem::where('user_id', $user->id)
                    ->where('event_type', $currentEventType)
                    ->exists();

                if (!$eventPaidInPastPeriod && $currentHighestPrice) {
                    $finalPrice = $this->calculatePrice($user->id, $currentEventType, $currentHighestPrice);

                    $totalPrice += $finalPrice;

                    $invoiceItems[] = [
                        'user_id' => $user->id,
                        'event_type' => $currentEventType,
                        'event_price' => $finalPrice,
                    ];
                }
            }

            if (count($invoiceItems)) {
                $invoice = Invoice::create([
                    'customer_id' => $customerId,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_price' => $totalPrice,
                ]);

                foreach ($invoiceItems as $item) {
                    $item['invoice_id'] = $invoice->id;
                    InvoiceItem::create($item);
                }

                DB::commit();

                return [
                    'data' => ['invoice_id' => $invoice->id],
                    'status' => 201,
                ];
            }

            throw new \Exception('No valid events found for invoicing.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Invoice generation failed', [
                'error' => $e->getMessage(),
                'customer_id' => $customerId,
            ]);

            return [
                'data' => ['error' => 'No valid events found for invoicing'],
                'status' => 500,
            ];
        }
    }

    /**
     * Calculate the registration price for a user in a given period.
     */
    private function calculateRegistrationPrice(User $user, string $startDate, string $endDate): int
    {
        return ($user->registered_at >= $startDate && $user->registered_at <= $endDate) ? 50 : 0;
    }

    /**
     * Calculate the activation price for a user in a given period.
     */
    private function calculateActivationPrice(User $user, string $startDate, string $endDate): int
    {
        return Session::where('user_id', $user->id)
            ->whereBetween('activated_at', [$startDate, $endDate])
            ->exists() ? 100 : 0;
    }

    /**
     * Calculate the appointment price for a user in a given period.
     */
    private function calculateAppointmentPrice(User $user, string $startDate, string $endDate): int
    {
        return Session::where('user_id', $user->id)
            ->whereBetween('appointment_at', [$startDate, $endDate])
            ->exists() ? 200 : 0;
    }

    /**
     * Get the price based on the latest event paid.
     */
    private function calculatePrice(int $userId, $currentEvent, $currentHighestPrice): int
    {
        $latestBilledEvent = InvoiceItem::where('user_id', $userId)
            ->latest()
            ->first();

        if ($latestBilledEvent && $latestBilledEvent->event_type === self::REGISTRATION && $currentEvent === self::ACTIVATION) {
            return $currentHighestPrice - $latestBilledEvent->event_price;
        }

        return $currentHighestPrice;
    }

    /**
     * Calculate event frequency for an invoice.
     */
    public function calculateEventFrequency(Invoice $invoice): \Illuminate\Database\Eloquent\Collection
    {
        return $invoice->items()
            ->select('event_type')
            ->selectRaw('COUNT(*) as frequency')
            ->groupBy('event_type')
            ->get();
    }
}
