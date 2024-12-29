<?php

namespace Database\Seeders;

use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CustomerSeeder::class,
            UserSeeder::class,
            SessionSeeder::class,
            InvoiceSeeder::class,
            InvoiceItemSeeder::class
        ]);
    }
}
