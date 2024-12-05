<?php

namespace Database\Seeders;

use App\Models\OrderRepair;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderRepairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // OrderRepair::truncate();
        for ($i = 1; $i <= 50; $i++) {
            OrderRepair::insert([
                'order_id' => $i,
                'repair_id' =>1,
                'user_id' => 1,

                // Estimated time fixed at 20 minutes (1200 seconds)
                'estimated_time' => '00:20:00',

                // Time taken with ±5 minutes range (15 to 25 minutes)
                'time_taken' => gmdate('H:i:s', rand(900, 1500)),
                'user_Xp' => rand(50, 100),
                'Xp' => rand(10, 50),
            ]);
        }
    }
}

