<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Rohan Napit',
            'email' => 'rohan.napit@gmail.com',
            'password' => Hash::make('password'),
            'isAdmin' => true,
            'email_verified_at'=>'2024-09-05 10:27:07'
        ]);
        $this->call([
            RepairSeeder::class,    
            PartSeeder::class,    
        ]);
    }
}
