<?php

namespace Database\Factories;

use App\Models\InvoiceItem;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition(): array
    {
        $eventTypes = [
            'registration' => 50,
            'activation' => 100,
            'appointment' => 200,
        ];

        $eventType = $this->faker->randomElement(array_keys($eventTypes));

        return [
            'invoice_id' => Invoice::factory(),
            'user_id' => User::factory(),
            'event_type' => $eventType,
            'event_price' => $eventTypes[$eventType],
        ];
    }
}
