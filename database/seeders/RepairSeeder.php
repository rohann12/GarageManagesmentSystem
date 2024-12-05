<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Repair;

class RepairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Repair::truncate();
        Repair::insert([
            // Low complexity repairs
            ['repair_complexity' => 'Low', 'repair_name' => 'Replace Brake Pads', 'estimated_duration' => '00:20:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Tire Air Adjustment', 'estimated_duration' => '00:10:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Chain Lubrication', 'estimated_duration' => '00:15:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Brake Adjustment', 'estimated_duration' => '00:20:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Replace Handlebar Grips', 'estimated_duration' => '00:10:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Replace Brake Lever', 'estimated_duration' => '00:25:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Tighten Bolts and Nuts', 'estimated_duration' => '00:15:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Adjust Seat Height', 'estimated_duration' => '00:10:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Lubricate Derailleur', 'estimated_duration' => '00:15:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Clean and Align Brake Pads', 'estimated_duration' => '00:25:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Fix Flat Tire (Patch)', 'estimated_duration' => '00:30:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Replace Flat Tube', 'estimated_duration' => '00:15:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Replace Pedals', 'estimated_duration' => '00:20:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Adjust Handlebar Angle', 'estimated_duration' => '00:10:00'],
            ['repair_complexity' => 'Low', 'repair_name' => 'Check and Adjust Tire Pressure', 'estimated_duration' => '00:10:00'],

            // Medium complexity repairs
            ['repair_complexity' => 'Medium', 'repair_name' => 'Tire Replacement', 'estimated_duration' => '00:40:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Replace Chain and Sprockets', 'estimated_duration' => '01:00:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Gear Shifting Adjustment', 'estimated_duration' => '00:45:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Replace Brake Cables', 'estimated_duration' => '00:50:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Suspension Tuning', 'estimated_duration' => '01:00:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Brake Disc Replacement', 'estimated_duration' => '01:10:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Replace Handlebar', 'estimated_duration' => '00:50:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Replace Bottom Bracket Bearings', 'estimated_duration' => '01:00:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'True Rear Wheel', 'estimated_duration' => '01:20:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Replace Freewheel', 'estimated_duration' => '01:10:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Replace Front Derailleur', 'estimated_duration' => '01:00:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Install Rear Rack', 'estimated_duration' => '00:45:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Install Fenders', 'estimated_duration' => '00:40:00'],
            ['repair_complexity' => 'Medium', 'repair_name' => 'Adjust Suspension Fork', 'estimated_duration' => '01:10:00'],

            // High complexity repairs
            ['repair_complexity' => 'High', 'repair_name' => 'Wheel Truing (Realignment)', 'estimated_duration' => '01:30:00'],
            ['repair_complexity' => 'High', 'repair_name' => 'Fork Replacement', 'estimated_duration' => '02:00:00'],
            ['repair_complexity' => 'High', 'repair_name' => 'Full Bike Overhaul', 'estimated_duration' => '03:30:00'],
            ['repair_complexity' => 'High', 'repair_name' => 'Replace Hydraulic Brake System', 'estimated_duration' => '02:00:00'],
            ['repair_complexity' => 'High', 'repair_name' => 'Frame Repair', 'estimated_duration' => '04:00:00'],
            ['repair_complexity' => 'High', 'repair_name' => 'Replace Bottom Bracket', 'estimated_duration' => '01:40:00'],
            ['repair_complexity' => 'High', 'repair_name' => 'Replace Rear Derailleur', 'estimated_duration' => '01:50:00'],
            ['repair_complexity' => 'High', 'repair_name' => 'Replace Wheelset', 'estimated_duration' => '02:30:00'],
            ['repair_complexity' => 'High', 'repair_name' => 'Replace Headset Bearings', 'estimated_duration' => '01:20:00'],
            ['repair_complexity' => 'High', 'repair_name' => 'Replace Crankset', 'estimated_duration' => '01:30:00'],
            ['repair_complexity' => 'High', 'repair_name' => 'Replace Shock Absorber', 'estimated_duration' => '02:30:00'],
            ['repair_complexity' => 'High', 'repair_name' => 'Rebuild Hydraulic Brakes', 'estimated_duration' => '02:00:00'],

            // Very high complexity repairs
            ['repair_complexity' => 'Very High', 'repair_name' => 'Electric Bike Motor Repair', 'estimated_duration' => '05:00:00'],
            ['repair_complexity' => 'Very High', 'repair_name' => 'Battery Replacement (Electric Bike)', 'estimated_duration' => '04:00:00'],
            ['repair_complexity' => 'Very High', 'repair_name' => 'Complex Suspension Overhaul', 'estimated_duration' => '06:00:00'],
            ['repair_complexity' => 'Very High', 'repair_name' => 'Custom Frame Build', 'estimated_duration' => '08:00:00'],
            ['repair_complexity' => 'Very High', 'repair_name' => 'Complete Drivetrain Replacement', 'estimated_duration' => '07:00:00'],
            ['repair_complexity' => 'Very High', 'repair_name' => 'Advanced Diagnostics (Electric Bikes)', 'estimated_duration' => '08:00:00'],
            ['repair_complexity' => 'Very High', 'repair_name' => 'Install Internal Gear Hub', 'estimated_duration' => '06:00:00'],
            ['repair_complexity' => 'Very High', 'repair_name' => 'Replace Electric Bike Wiring', 'estimated_duration' => '05:30:00'],
            ['repair_complexity' => 'Very High', 'repair_name' => 'Rebuild Electric Bike Motor', 'estimated_duration' => '06:30:00'],
            ['repair_complexity' => 'Very High', 'repair_name' => 'Custom Suspension Tuning', 'estimated_duration' => '07:00:00'],
            ['repair_complexity' => 'Very High', 'repair_name' => 'Rebuild Wheel Hub Motor', 'estimated_duration' => '06:30:00'],
        ]);
    }
}
