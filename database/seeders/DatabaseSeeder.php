<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Products;
use App\Models\Sales;
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
        $users = [
            [
                'name' => 'danaz',
                'username' => 'danaz',
                'phone' => '123412341234',
                'email' => 'danaz@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
            ],
            [
                'name' => 'chra',
                'username' => 'chra',
                'phone' => '121212341234',
                'email' => 'chra@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
            ],
            [
                'name' => 'gilas',
                'username' => 'gilas',
                'phone' => '07501122110',
                'email' => 'gilas@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
            ],
            [
                'name' => 'ahmad',
                'username' => 'ahmad',
                'phone' => '07501122111',
                'email' => 'ahmad@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
            ],
            [
                'name' => 'heshu',
                'username' => 'heshu',
                'phone' => '07502122110',
                'email' => 'heshu@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
            ],
            [
                'name' => 'razhan',
                'username' => 'razhan',
                'phone' => '07502122232',
                'email' => 'razhan@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
            ],
            [
                'name' => 'ramyar',
                'username' => 'ramyar',
                'phone' => '07512122232',
                'email' => 'ramyar@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
            ],
            [
                'name' => 'chenar',
                'username' => 'chenar',
                'phone' => '07511212232',
                'email' => 'chenar@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
            ],
            [
                'name' => 'raid',
                'username' => 'raid',
                'phone' => '07511222232',
                'email' => 'raid@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
            ],
            [
                'name' => 'muhammad',
                'username' => 'hama',
                'phone' => '07501842910',
                'email' => 'ihama9728@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => 1,
                'status' => 1,
            ],
            [
                'name' => 'zhyar',
                'username' => 'zhyar',
                'phone' => '07511209232',
                'email' => 'zhyar@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
            ],
            [
                'name' => 'didan',
                'username' => 'didan',
                'phone' => fake()->unique()->phoneNumber(),
                'email' => 'didan@gmail.com',
                'password' => Hash::make('muhammad'),
                'role' => fake()->numberBetween(1, 2),
                'status' => fake()->numberBetween(0, 1),
            ],
        ];
        foreach ($users as $user) {
            $users = \App\Models\User::create($user + [
                'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
            ]);
            $users->user_details()->create([
                'address' => fake()->randomElement(['ranya', 'Sulimany', 'Qaladzi', 'Hallshow']),
            ]);
        }


        // // \App\Models\User::factory(20)->create();
        // foreach (range(1, 1000) as $index) {
        //     $name = fake()->name;
        //     $name = Str::replace(' ', '', $name);
        //     $name = Str::replace('-', '', $name);
        //     $name = Str::replace('.', '', $name);
        //     $name = Str::limit($name, 7, '');

        //     $phone = fake()->phoneNumber;
        //     $phone  = Str::remove('+', $phone);
        //     $phone  = Str::remove(' ', $phone);
        //     $user = \App\Models\User::create([
        //         'name' => $name,
        //         'username' => fake()->unique()->userName . '-' . $index . $name,
        //         'phone' => $phone,
        //         'email' => fake()->unique()->safeEmail,
        //         'password' => Hash::make('muhammad'),
        //         'role' => fake()->numberBetween(1, 2),
        //         'status' => fake()->numberBetween(0, 1),
        //         'status' => fake()->numberBetween(0, 1),
        //         'created_at' => fake()->dateTimeBetween('-1 years', 'now'),
        //     ]);
        //     $user->user_details()->create([
        //         'address' => fake()->randomElement(['ranya', 'sulimany', 'qaladzi', 'hawler', 'Hallshow']),
        //     ]);
        // }
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
                'name' => 'darzi 2',
                'slug' => Str::slug('darzi 2'),
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
                'name' => 'darzi ddan',
                'slug' => Str::slug('darzi ddan'),
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
            \App\Models\Categorys::create($category);
        }
        // foreach (range(1, 10) as $index) {

        //     $name = fake()->name . fake()->name;
        //     $name = Str::replace(' ', '', $name);
        //     $name = Str::replace('-', '', $name);
        //     $name = Str::limit($name, 6, '');
        //     \App\Models\Categorys::create([
        //         'name' => $name,
        //         'slug' => Str::slug($name),
        //     ]);
        // }

        $suppliers = [
            [
                'name' => 'danaz',
                'phone' => '123412341234',
                'email' => 'danaz@gmail.com',
                'address' => 'iraq',
            ],
            [
                'name' => 'chra',
                'phone' => '121212341234',
                'email' => 'chra@gmail.com',
                'address' => 'iraq'
            ],
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
            \App\Models\Suppliers::create($supplier);
        }
        // foreach (range(1, 10) as $index) {
        //     $name = fake()->name;
        //     $name = Str::replace(' ', '', $name);
        //     $name = Str::replace('-', '', $name);
        //     $name = Str::lower($name);
        //     $name = Str::replace('.', '', $name);
        //     $name = Str::limit($name, 7, '');

        //     $phone = fake()->phoneNumber;
        //     $phone  = Str::remove('+', $phone);
        //     $phone  = Str::remove(' ', $phone);
        //     \App\Models\Suppliers::create([
        //         'name' => $name,
        //         'phone' => $phone,
        //         'email' =>  fake()->unique()->safeEmail,
        //         'address' => fake()->address,
        //     ]);
        // }

        $purches_price = 250;
        $sale_price = 500;
        $barcode = 1;
        foreach (range(1, 20) as $index) {
            $name = fake()->name;
            $name = Str::lower($name);
            $name = Str::replace('.', '', $name);
            $products = \App\Models\Products::create([
                'name' => $name,
                'barcode' => $barcode++,
                'quantity' => fake()->numberBetween(1, 1100000),
                'expiry_date' => fake()->dateTimeBetween('now', '+10 years')->format('Y-m-d'),
                'purches_price' => $purches_price += 250,
                'sale_price' => $sale_price += 500,
                'category_id' => fake()->numberBetween(1, 22),
                'supplier_id' => fake()->numberBetween(1, 21),
            ]);
        }
        $barcode2 = 21;
        foreach (range(1, 20) as $index) {
            $name = fake()->name;
            $name = Str::lower($name);
            $name = Str::replace('.', '', $name);
            $products = \App\Models\Products::create([
                'name' => $name,
                'barcode' => $barcode2++,
                'quantity' => fake()->numberBetween(1, 1100000),
                'expiry_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'purches_price' => fake()->numberBetween(1, 1000000),
                'sale_price' => fake()->numberBetween(1, 1000000),
                'category_id' => fake()->numberBetween(1, 22),
                'supplier_id' => fake()->numberBetween(1, 21),
            ]);
        }

        $barcode3 = 41;
        foreach (range(1, 20) as $index) {
            $name = fake()->name;
            $name = Str::lower($name);
            $name = Str::replace('.', '', $name);
            $products = \App\Models\Products::create([
                'name' => $name,
                'barcode' => $barcode3++,
                'quantity' => 0,
                'expiry_date' => fake()->dateTimeBetween('now', '+1 years')->format('Y-m-d'),
                'purches_price' => fake()->numberBetween(1, 1000000),
                'sale_price' => fake()->numberBetween(1, 1000000),
                'category_id' => fake()->numberBetween(1, 22),
                'supplier_id' => fake()->numberBetween(1, 21),
            ]);
        }

        foreach (range(1, 10) as $index) {
            $number = fake()->unique()->numberBetween(0, 2147483647) . Str::random(1);
            $number = Str::limit($number, 9, '');
            $invoice = Str::start($number, 'inv-');
            $sales = Sales::create([
                'invoice' => $invoice,
                'user_id' => User::inRandomOrder()->first()->id,
                'total' => fake()->numberBetween(1, 1000000),
                'discount' => fake()->numberBetween(0, 100),
                'status' => 1,
            ]);
            foreach (range(1, 3) as $index) {
                $sales->sale_details()->create([
                    'product_id' => Products::inRandomOrder()->first()->id,
                    'quantity' => fake()->numberBetween(1, 100),
                ]);
            }
        }
        foreach (range(1, 4) as $index) {
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
            ]);
            $sales->debt_sale()->create([
                'name' => fake()->name,
                'phone' => fake()->phoneNumber,
                'amount' => $sales->total,
                'paid' => fake()->numberBetween(0, $sales->total),
                'remain' => $sales->total - fake()->numberBetween(0, $sales->total),
            ]);
            foreach (range(1, 10) as $index) {
                $sales->sale_details()->create([
                    'product_id' => Products::inRandomOrder()->first()->id,
                    'quantity' => fake()->numberBetween(1, 100),
                ]);
            }
        }

        \App\Models\Settings::create([
            'name' => 'Pharmacy System',
            'phone' => '07501111111',
            'email' => 'pharmacy@pharmacy.com',
            'address' => 'Ranya / Sulimany',
        ]);
    }
}
