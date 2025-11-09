<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory()->create([
        //     'name' => 'Mahmoud Eltwansy',
        //     'email' => 'mahmoudtwansy1999@gmail.com',
        //     'password'=>'password',
        // ]);

        // $this->call(UserSeeder::class);
        // User::factory(10)->create();
        // Category::factory(5)->create();
        // Store::factory(5)->create();
        // Product::factory(10)->create();
        Admin::factory(3)->create();
    }
}
