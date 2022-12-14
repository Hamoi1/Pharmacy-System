<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Categorys;
use App\Models\Products;
use App\Models\Sales;
use App\Models\Suppliers;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Settings::create([
            'name' => 'Pharmacy System',
            'phone' => '07501111111',
            'email' => 'pharmacy@pharmacy.com',
            'address' => 'Ranya / Sulimany',
        ]);
        $users = [
            [
                'name' => 'muhammad',
                'username' => 'hama',
                'phone' => fake()->unique()->phoneNumber(),
                'email' => 'ihama9728@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => 1,
                'status' => 1,
            ],
            [
                'name' => 'muhammad cashier',
                'username' => 'hama_cashier',
                'phone' => fake()->unique()->phoneNumber(),
                'email' => 'cashier@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => 2,
                'status' => 1,
            ],
        ];
        foreach ($users as $user) {
            $users = \App\Models\User::create($user + [
                'created_at' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ]);
            $users->user_details()->create([
                'address' => fake()->randomElement(['ranya', 'Sulimany', 'Qaladzi', 'Hallshow']),
            ]);
        }
        foreach (range(1, 1000) as $index) {
            $name = fake()->name;
            $name = Str::replace(' ', '', $name);
            $name = Str::replace('-', '', $name);
            $name = Str::replace('.', '', $name);
            $name = Str::limit($name, 7, '');

            $phone = fake()->phoneNumber;
            $phone  = Str::remove('+', $phone);
            $phone  = Str::remove(' ', $phone);
            $user = \App\Models\User::create([
                'name' => $name,
                'username' => fake()->unique()->userName . '-' . $index . $name,
                'phone' => $phone,
                'email' => fake()->unique()->safeEmail,
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
                'status' => fake()->numberBetween(0, 1),
                'created_at' => fake()->randomElement([fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'), 'now'])
            ]);
            $user->user_details()->create([
                'address' => fake()->randomElement(['ranya', 'sulimany', 'qaladzi', 'hawler', 'Hallshow']),
            ]);
        }
        $catagorys_data = [
            [
                'name' => 'darzi',
                'slug' => Str::slug('darzi'),
            ],
            [
                'name' => 'shrubi mndallan',
                'slug' => Str::slug('shrubi mndallan'),
            ],
            [
                'name' => '3abi saresha',
                'slug' => Str::slug('3abi saresha'),
            ],
            [
                'name' => 'shrubi zghesha',
                'slug' => Str::slug('shrubi zghesha'),
            ],
            [
                'name' => 'mal3ami dmwchaw',
                'slug' => Str::slug('mal3ami dmwchaw'),
            ],
            [
                'name' => 'shrub',
                'slug' => Str::slug('shrub'),
            ],
            [
                'name' => '3ab',
                'slug' => Str::slug('3ab'),
            ],
            [
                'name' => 'krim',
                'slug' => Str::slug('krim'),
            ],
            [
                'name' => 'qatra',
                'slug' => Str::slug('qatra'),
            ],
            [
                'name' => 'mal3am',
                'slug' => Str::slug('mal3am'),
            ],
        ];
        foreach ($catagorys_data as $category) {
            \App\Models\Categorys::create($category, [
                'created_at' => fake()->dateTimeBetween('-1 years', 'now'),
            ]);
        }
        foreach (range(1, 100) as $index) {
            $name = fake()->name . fake()->name;
            \App\Models\Categorys::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'created_at' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ]);
        }

        $suppliers = [
            [
                'name' => 'gilas',
                'phone' => '07501122110',
                'email' => 'gilas@gmail.com',
                'address' => 'iraq'
            ],
            [
                'name' => 'ahmad',
                'phone' => '07501122111',
                'email' => 'ahmad@gmail.com',
                'address' => 'iraq'
            ],
            [
                'name' => 'heshu',
                'phone' => '07502122110',
                'email' => 'heshu@gmail.com',
                'address' => 'iraq'
            ],
            [
                'name' => 'razhan',
                'phone' => '07502122232',
                'email' => 'razhan@gmail.com',
                'address' => 'iraq'
            ],
            [
                'name' => 'muhammad',
                'phone' => '075022232',
                'email' => 'muhammad@gmail.com',
                'address' => 'iraq'
            ],
            [
                'name' => 'ramyar',
                'phone' => '07512122232',
                'email' => 'ramyar@gmail.com',
                'address' => 'iraq'
            ],
            [
                'name' => 'chenar',
                'phone' => '07511212232',
                'email' => 'chenar@gmail.com',
                'address' => 'iraq'
            ],
            [
                'name' => 'raid',
                'phone' => '07511222232',
                'email' => 'raid@gmail.com',
                'address' => 'iraq'
            ],
        ];
        foreach ($suppliers as $supplier) {
            \App\Models\Suppliers::create($supplier, [
                'created_at' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ]);
        }
        foreach (range(1, 100) as $index) {
            $name = fake()->name;
            $name = Str::replace(' ', '', $name);
            $name = Str::replace('-', '', $name);
            $name = Str::lower($name);
            $name = Str::replace('.', '', $name);
            $name = Str::limit($name, 7, '');

            $phone = fake()->phoneNumber;
            $phone  = Str::remove('+', $phone);
            $phone  = Str::remove(' ', $phone);
            \App\Models\Suppliers::create([
                'name' => $name,
                'phone' => $phone,
                'email' =>  fake()->unique()->safeEmail,
                'address' => fake()->address,
                'created_at' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ]);
        }

        // expiry product
        $barcode = 1;
        foreach (range(1, 200) as $index) {
            $name = fake()->name;
            $name = Str::lower($name);
            $name = Str::replace('.', '', $name);
            \App\Models\Products::create([
                'name' => $name,
                'barcode' => $barcode++,
                'quantity' => fake()->numberBetween(1, 1100000),
                'expiry_date' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
                'purches_price' => fake()->numberBetween(1, 1000000),
                'sale_price' => fake()->numberBetween(1, 1000000),
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                'created_at' => now(),
            ]);
        }

        // stock out product
        $barcode = 202;
        foreach (range(1, 100) as $index) {
            $name = fake()->name;
            $name = Str::lower($name);
            $name = Str::replace('.', '', $name);
            \App\Models\Products::create([
                'name' => $name,
                'barcode' => $barcode++,
                'quantity' => 0,
                'expiry_date' => fake()->dateTimeBetween('now', '+1 years')->format('Y-m-d'),
                'purches_price' => fake()->numberBetween(1, 1000000),
                'sale_price' => fake()->numberBetween(1, 1000000),
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                'created_at' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ]);
        }

        // stock out product
        $barcode = 302;
        foreach (range(1, 4000) as $index) {
            $name = fake()->name;
            $name = Str::lower($name);
            $name = Str::replace('.', '', $name);
            \App\Models\Products::create([
                'name' => $name,
                'barcode' => $barcode++,
                'quantity' => fake()->numberBetween(1, 1100000),
                'expiry_date' => fake()->dateTimeBetween('now', '+3 years')->format('Y-m-d'),
                'purches_price' => fake()->numberBetween(1, 1000000),
                'sale_price' => fake()->numberBetween(1, 1000000),
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                'created_at' => now(),
            ]);
        }

        foreach (range(1, 400) as $index) {
            $number = fake()->unique()->numberBetween(0, 2147483647) . Str::random(1);
            $number = Str::limit($number, 9, '');
            $invoice = Str::start($number, 'inv-');
            $sales = Sales::create([
                'invoice' => $invoice,
                'user_id' => User::inRandomOrder()->first()->id,
                'total' => fake()->numberBetween(1, 1000000),
                'discount' => fake()->numberBetween(0, 100),
                'status' => 1,
                'created_at' => now(),
            ]);
            foreach (range(1, 8) as $index) {
                $sales->sale_details()->create([
                    'product_id' => Products::inRandomOrder()->first()->id,
                    'quantity' => fake()->numberBetween(1, 100),
                    'created_at' => now(),
                ]);
            }
        }

        foreach (range(1, 40) as $index) {
            $number = fake()->unique()->numberBetween(0, 2147483647) . Str::random(1);
            $number = Str::limit($number, 9, '');
            $invoice = Str::start($number, 'inv-');
            $sales = Sales::create([
                'invoice' => $invoice,
                'user_id' => User::inRandomOrder()->first()->id,
                'total' => fake()->numberBetween(1, 1000000),
                'discount' => fake()->numberBetween(0, 100),
                'status' => 1,
                'paid' => 0,
                'created_at' => now(),
            ]);
            $sales->debt_sale()->create([
                'name' => fake()->name,
                'phone' => fake()->phoneNumber,
                'amount' => $sales->total,
                'paid' => fake()->numberBetween(0, $sales->total),
                'remain' => $sales->total - fake()->numberBetween(0, $sales->total),
                'created_at' => now(),
            ]);
            foreach (range(1, 20) as $index) {
                $sales->sale_details()->create([
                    'product_id' => Products::inRandomOrder()->first()->id,
                    'quantity' => fake()->numberBetween(1, 100),
                    'created_at' => now(),
                ]);
            }
        }
    }
}
