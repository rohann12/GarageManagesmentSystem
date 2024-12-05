<?php

namespace Database\Seeders;

use App\Models\Part;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Part::insert([
            ['part_name' => 'Brake Pad', 'description' => 'Front brake pad', 'price' => 1200, 'quantity' => 50, 'status' => 'available'],
            ['part_name' => 'Clutch Plate', 'description' => 'Complete set of clutch plates', 'price' => 2500, 'quantity' => 30, 'status' => 'available'],
            ['part_name' => 'Engine Oil', 'description' => 'Synthetic engine oil, 1L', 'price' => 600, 'quantity' => 100, 'status' => 'available'],
            ['part_name' => 'Chain Sprocket', 'description' => 'Front and rear chain sprocket set', 'price' => 3500, 'quantity' => 20, 'status' => 'available'],
            ['part_name' => 'Air Filter', 'description' => 'High-performance air filter', 'price' => 800, 'quantity' => 60, 'status' => 'available'],
            ['part_name' => 'Headlight Bulb', 'description' => 'LED headlight bulb', 'price' => 500, 'quantity' => 100, 'status' => 'available'],
            ['part_name' => 'Battery', 'description' => '12V motorcycle battery', 'price' => 2500, 'quantity' => 40, 'status' => 'available'],
            ['part_name' => 'Brake Lever', 'description' => 'Adjustable brake lever', 'price' => 700, 'quantity' => 35, 'status' => 'available'],
            ['part_name' => 'Exhaust', 'description' => 'Aftermarket exhaust system', 'price' => 5000, 'quantity' => 15, 'status' => 'available'],
            ['part_name' => 'Fuel Pump', 'description' => 'OEM fuel pump', 'price' => 4000, 'quantity' => 25, 'status' => 'available'],
            ['part_name' => 'Spark Plug', 'description' => 'Iridium spark plug', 'price' => 300, 'quantity' => 200, 'status' => 'available'],
            ['part_name' => 'Handlebar', 'description' => 'Aluminum handlebar', 'price' => 1500, 'quantity' => 20, 'status' => 'available'],
            ['part_name' => 'Side Mirror', 'description' => 'Left and right side mirrors', 'price' => 600, 'quantity' => 70, 'status' => 'available'],
            ['part_name' => 'Foot Peg', 'description' => 'Front foot pegs', 'price' => 800, 'quantity' => 40, 'status' => 'available'],
            ['part_name' => 'Radiator', 'description' => 'High-performance radiator', 'price' => 5500, 'quantity' => 10, 'status' => 'available'],
            ['part_name' => 'Fuel Tank', 'description' => 'Fuel tank with cap', 'price' => 8000, 'quantity' => 5, 'status' => 'available'],
            ['part_name' => 'Suspension', 'description' => 'Front and rear suspension kit', 'price' => 10000, 'quantity' => 8, 'status' => 'available'],
            ['part_name' => 'Gear Shifter', 'description' => 'Aftermarket gear shifter', 'price' => 900, 'quantity' => 25, 'status' => 'available'],
            ['part_name' => 'Radiator Hose', 'description' => 'Reinforced radiator hose', 'price' => 400, 'quantity' => 50, 'status' => 'available'],
            ['part_name' => 'Throttle Cable', 'description' => 'Durable throttle cable', 'price' => 350, 'quantity' => 60, 'status' => 'available'],
            ['part_name' => 'Windshield', 'description' => 'Acrylic windshield', 'price' => 1200, 'quantity' => 30, 'status' => 'available'],
            ['part_name' => 'Piston', 'description' => 'High-performance piston', 'price' => 3000, 'quantity' => 20, 'status' => 'available'],
            ['part_name' => 'Kickstand', 'description' => 'Heavy-duty kickstand', 'price' => 1000, 'quantity' => 25, 'status' => 'available'],
            ['part_name' => 'Wheel Rim', 'description' => 'Alloy wheel rim', 'price' => 7000, 'quantity' => 10, 'status' => 'available'],
            ['part_name' => 'Tire', 'description' => 'Tubeless tire', 'price' => 5000, 'quantity' => 30, 'status' => 'available'],
            ['part_name' => 'Disc Rotor', 'description' => 'Front disc brake rotor', 'price' => 2000, 'quantity' => 20, 'status' => 'available'],
            ['part_name' => 'Seat Cover', 'description' => 'Leather seat cover', 'price' => 1000, 'quantity' => 40, 'status' => 'available'],
            ['part_name' => 'Brake Caliper', 'description' => 'Dual-piston brake caliper', 'price' => 3500, 'quantity' => 15, 'status' => 'available'],
            ['part_name' => 'Speedometer', 'description' => 'Digital speedometer', 'price' => 2500, 'quantity' => 12, 'status' => 'available'],
            ['part_name' => 'Chain Guard', 'description' => 'Durable chain guard', 'price' => 600, 'quantity' => 50, 'status' => 'available'],
            ['part_name' => 'Handle Grips', 'description' => 'Comfortable handle grips', 'price' => 300, 'quantity' => 70, 'status' => 'available'],
            ['part_name' => 'Clutch Cable', 'description' => 'Heavy-duty clutch cable', 'price' => 400, 'quantity' => 60, 'status' => 'available'],
            ['part_name' => 'Tail Light', 'description' => 'LED tail light', 'price' => 600, 'quantity' => 50, 'status' => 'available'],
            ['part_name' => 'Horn', 'description' => 'Dual-tone horn', 'price' => 800, 'quantity' => 40, 'status' => 'available'],
            ['part_name' => 'Ignition Coil', 'description' => 'High-performance ignition coil', 'price' => 1800, 'quantity' => 30, 'status' => 'available'],
            ['part_name' => 'Cooling Fan', 'description' => 'Electric cooling fan', 'price' => 1500, 'quantity' => 20, 'status' => 'available'],
            ['part_name' => 'Front Fork', 'description' => 'Front fork assembly', 'price' => 4000, 'quantity' => 10, 'status' => 'available'],
            ['part_name' => 'Rearview Camera', 'description' => 'Rearview camera with display', 'price' => 6000, 'quantity' => 5, 'status' => 'available'],
            ['part_name' => 'Frame Slider', 'description' => 'Crash protection frame slider', 'price' => 3500, 'quantity' => 15, 'status' => 'available'],
        ]);
    }

}
