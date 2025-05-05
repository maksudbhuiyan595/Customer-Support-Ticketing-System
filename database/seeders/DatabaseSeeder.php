<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::create([
            'id'=>1,
            'name'=>'admin',
            'email'=>'admin@gmail.com',
            'password'=>bcrypt('12345678'),
            'verify_email'=>1,
            'role'=>'ADMIN',
        ]);
        User::create([
            'id'=>2,
            'name'=>'cusomer',
            'email'=>'customer@gmail.com',
            'password'=>bcrypt('12345678'),
            'verify_email'=>1,
            'role'=>'CUSTOMER',
        ]);

    }
}
