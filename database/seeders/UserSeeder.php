<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $customerId = 1;

        User::updateOrCreate([
            'customer_id' => $customerId,
            'email' => 'usera@example.com',
            'registered_at' => '2020-12-01',
        ]);

        User::updateOrCreate([
            'customer_id' => $customerId,
            'email' => 'userb@example.com',
            'registered_at' => '2020-12-15',
        ]);

        User::updateOrCreate([
            'customer_id' => $customerId,
            'email' => 'userc@example.com',
            'registered_at' => '2021-01-01',
        ]);

        User::updateOrCreate([
            'customer_id' => $customerId,
            'email' => 'userd@example.com',
            'registered_at' => '2020-09-01',
        ]);
    }
}
