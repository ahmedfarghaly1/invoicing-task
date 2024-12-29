<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Session;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $userA = 1;
        $userB = 2;
        $userC = 3;
        $userD = 4;

        // Sessions for User A
        Session::updateOrCreate([
            'user_id' => $userA,
            'activated_at' => '2021-01-15',
        ]);
        Session::updateOrCreate([
            'user_id' => $userA,
            'activated_at' => '2021-01-18',
        ]);

        // Sessions for User B
        Session::updateOrCreate([
            'user_id' => $userB,
            'appointment_at' => '2021-01-15',
        ]);

        // Sessions for User C
        Session::updateOrCreate([
            'user_id' => $userC,
            'activated_at' => '2021-01-10',
        ]);

        // Sessions for User D
        Session::updateOrCreate([
            'user_id' => $userD,
            'activated_at' => '2020-10-11',
        ]);
        Session::updateOrCreate([
            'user_id' => $userD,
            'activated_at' => '2021-01-12',
        ]);
        Session::updateOrCreate([
            'user_id' => $userD,
            'appointment_at' => '2020-12-27',
        ]);
        Session::updateOrCreate([
            'user_id' => $userD,
            'appointment_at' => '2021-01-22',
        ]);
    }
}
