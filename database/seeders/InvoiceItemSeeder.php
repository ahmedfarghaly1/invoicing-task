<?php

namespace Database\Seeders;

use App\Services\InvoiceService;
use Illuminate\Database\Seeder;
use App\Models\InvoiceItem;

class InvoiceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $userA = 1;
        $userD = 4;
        InvoiceItem::updateOrCreate([
            'invoice_id' => 1,
            'user_id' => $userD,
            'event_type' => InvoiceService::ACTIVATION,
            'event_price' => 100,
        ]);

        InvoiceItem::updateOrCreate([
            'invoice_id' => 2,
            'user_id' => $userA,
            'event_type' => InvoiceService::REGISTRATION,
            'event_price' => 50,
        ]);

        InvoiceItem::updateOrCreate([
            'invoice_id' => 2,
            'user_id' => $userD,
            'event_type' => InvoiceService::APPOINTMENT,
            'event_price' => 200,
        ]);





    }
}
