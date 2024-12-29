<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Invoice::updateOrCreate([
            'customer_id' => 1,
            'start_date' => '2020-10-01',
            'end_date' => '2020-10-31',
            'total_price' => 100,
        ]);
        Invoice::updateOrCreate([
            'customer_id' => 1,
            'start_date' => '2020-12-01',
            'end_date' => '2020-12-31',
            'total_price' => 200,
        ]);
    }
}


