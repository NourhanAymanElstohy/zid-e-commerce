<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            RoleSeeder::class,
        ]);

        $u1 = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        $u2 = User::create([
            'name' => 'Test 2 User',
            'email' => 'test2@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        $u3 = User::create([
            'name' => 'Consum 1',
            'email' => 'cons@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        $u1->assignRole('merchant');
        $u2->assignRole('merchant');
        $u3->assignRole('consumer');

        for ($i = 0; $i < 10; $i++) {
            Store::create([
                'user_id' => rand(1, 2),
                'name' => 'store' . $i,
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            Product::create([
                'store_id' => rand(1, 10),
                'name_en' => Str::random(4),
                'name_ar' => Str::random(6),
                'description_en' =>  Str::random(100),
                'description_ar' => Str::random(100),
                'price' => rand(2.5, 5),
                'quantity' => rand(0, 7),
                'shipping_cost' => rand(0, 400),
                'is_vat_included' => false,
                'vat_percentage' => 0
            ]);
        }
    }
}
