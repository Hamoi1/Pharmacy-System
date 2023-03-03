<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use App\Models\Sales;
use App\Models\Products;
use App\Models\Categorys;
use App\Models\Suppliers;
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
        $roles = [
            'View Dashboard',
            'View Sales',
            'View DebtSale',
            'View Product',
            'View User',
            'View ExpiryProduct',
            'View StockOutProduct',
            'View Category',
            'View Supplier',
            'View Barcode',
            'View Setting',
            'View Log',

            'Delete Sales',
            'Delete DebtSale',
            'Delete Product',
            'Delete User',
            'Delete Category',
            'Delete Supplier',
            'Delete Barcode',
            'Delete Logs',

            'Insert Sales',
            'Insert Product',
            'Insert User',
            'Insert Category',
            'Insert Supplier',
            'Insert Barcode',

            'Update Sales',
            'Update DebtSale',
            'Update Product',
            'Update User',
            'Update Category',
            'Update Supplier',
            'Update Barcode',

            'Product Trash',
            'User Trash',
            'Category Trash',
            'Supplier Trash',
            'Clear Log',

        ];

        foreach ($roles as $role) {
            Role::insert([
                'name' => $role
            ]);
        }

        $users = [
            [
                'name' => 'muhammad',
                'username' => 'hama',
                'phone' => fake()->unique()->phoneNumber(),
                'email' => 'ihama9728@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
            [
                'name' => 'hogr',
                'username' => 'hogr',
                'phone' => fake()->unique()->phoneNumber(),
                'email' => 'hogr@gmail.com',
                'password' => Hash::make('muhammad'),
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
        foreach (range(1, 10) as $index) {
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
                'status' => fake()->numberBetween(0, 1),
                'status' => fake()->numberBetween(0, 1),
                'created_at' => fake()->randomElement([fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'), 'now'])
            ]);
            $user->user_details()->create([
                'address' => fake()->randomElement(['ranya', 'sulimany', 'qaladzi', 'hawler', 'Hallshow']),
            ]);
        }
        // give permission for all user
        $users = User::take(4)->get();
        $roles = Role::all();
        foreach ($users as $user) {
            foreach ($roles as $role) {
                $user->Permissions()->create([
                    // give role_id and user_id
                    'role_id' => $role->id,
                    'user_id' => $user->id,
                ]);
            }
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
        foreach (range(1, 10) as $index) {
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
                'name' => 'mihraban',
                'phone' => '07511212232',
                'email' => 'mihraban@gmail.com',
                'address' => 'iraq'
            ],
            [
                'name' => 'danaz',
                'phone' => '07511222232',
                'email' => 'danaz@gmail.com',
                'address' => 'iraq'
            ],
            [
                'name' => 'sazyar',
                'phone' => '07511222232',
                'email' => 'sazyar@gmail.com',
                'address' => 'iraq'
            ],
            [
                'name' => 'savyar',
                'phone' => '07511222232',
                'email' => 'savyar@gmail.com',
                'address' => 'iraq'
            ],
        ];
        foreach ($suppliers as $supplier) {
            \App\Models\Suppliers::create($supplier, [
                'created_at' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ]);
        }
        foreach (range(1, 10) as $index) {
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
        foreach (range(1, 50) as $index) {
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
        $barcode = 51;
        foreach (range(1, 50) as $index) {
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
        $barcode = 101;
        foreach (range(1, 40) as $index) {
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

        foreach (range(1, 20) as $index) {
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
            foreach (range(1, rand(1, 9)) as $index) {
                $sales->sale_details()->create([
                    'product_id' => Products::inRandomOrder()->first()->id,
                    'quantity' => fake()->numberBetween(1, 100),
                    'created_at' => now(),
                ]);
            }
        }

        foreach (range(1, 20) as $index) {
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
            foreach (range(1, rand(1, 9)) as $index) {
                $sales->sale_details()->create([
                    'product_id' => Products::inRandomOrder()->first()->id,
                    'quantity' => fake()->numberBetween(1, 100),
                    'created_at' => now(),
                ]);
            }
        }
    }
}
