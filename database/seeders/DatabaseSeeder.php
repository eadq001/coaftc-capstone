<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call(EmployeeSeeder::class);

    //    User::factory()->create([
    //        'email' => 'eadq1999@gmail.com',
    //        'password' => '12345678',
    //        'user_role' => 'admin',
    //    ]);

        User::factory()->create([
            'email' => 'ashley@gmail.com',
            'password' => '12345678',
            'user_role' => 'admin',
        ]);
        User::factory()->create([
            'email' => 'khim@gmail.com',
            'password' => '12345678',
            'user_role' => 'inventory_clerk',
        ]);
        User::factory()->create([
            'email' => 'dave@gmail.com',
            'password' => '12345678',
            'user_role' => 'cashier',
        ]);
    }
}
