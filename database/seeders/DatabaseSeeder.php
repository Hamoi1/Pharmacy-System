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
            'User GenerateReport',
            'User Export',
            'Product Export'
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
                'phone' => Str::limit(rand(1, 99999999999), 11, ''),
                'email' => 'ihama9728@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
            [
                'name' => 'hogr',
                'username' => 'hogr',
                'phone' => Str::limit(rand(1, 99999999999), 11, ''),
                'email' => 'hogr@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
            [
                'name' => 'aven',
                'username' => 'aven',
                'phone' => Str::limit(rand(1, 9999999999), 11, ''),
                'email' => 'aven@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
            [
                'name' => 'evan',
                'username' => 'evan',
                'phone' => Str::limit(rand(1, 9999999999), 11, ''),
                'email' => 'evan@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
            [
                'name' => 'chra',
                'username' => 'chra',
                'phone' => Str::limit(rand(1, 9999999999), 11, ''),
                'email' => 'chra@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
            [
                'name' => 'redin',
                'username' => 'redin',
                'phone' => Str::limit(rand(1, 9999999999), 11, ''),
                'email' => 'redin@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
            [
                'name' => 'gashtyar',
                'username' => 'gashtyar',
                'phone' => Str::limit(rand(1, 9999999999), 11, ''),
                'email' => 'gashtyar@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
            [
                'name' => 'danaz',
                'username' => 'danaz',
                'phone' => Str::limit(rand(1, 9999999999), 11, ''),
                'email' => 'danaz@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
            [
                'name' => 'rekar',
                'username' => 'rekar',
                'phone' => Str::limit(rand(1, 99999999999), 11, ''),
                'email' => 'rekar@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
            [
                'name' => 'akar',
                'username' => 'akar',
                'phone' => Str::limit(rand(1, 99999999999), 11, ''),
                'email' => 'akar@gmail.com',
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
        // foreach (range(1, 110) as $index) {
        //     $name = fake()->name;
        //     $name = Str::replace(' ', '', $name);
        //     $name = Str::replace('-', '', $name);
        //     $name = Str::replace('.', '', $name);
        //     $name = Str::limit($name, 7, '');

        //     $phone = fake()->phoneNumber;
        //     $phone  = Str::remove('+', $phone);
        //     $phone  = Str::remove(' ', $phone);
        //     $phone  = Str::remove('-', $phone);
        //     $phone = Str::limit($phone, 11, '');
        //     $user = \App\Models\User::create([
        //         'name' => $name,
        //         'username' => fake()->unique()->userName . '-' . $index . $name,
        //         'phone' => $phone,
        //         'email' => fake()->unique()->safeEmail,
        //         'password' => Hash::make('muhammad'),
        //         'status' => fake()->numberBetween(0, 1),
        //         'created_at' => fake()->randomElement([fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'), 'now'])
        //     ]);
        //     $user->user_details()->create([
        //         'address' => fake()->randomElement(['ranya', 'sulimany', 'qaladzi', 'hawler', 'Hallshow']),
        //     ]);
        // }

        // give permission for all user
        $users = User::take(11)->get();
        $roles = Role::all();
        foreach ($roles as $role) {
            foreach ($users as $user) {
                $user->Permissions()->create([
                    'role_id' => $role->id,
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
        $medicines = [
            [
                "name" => "Aspirin",
                "barcode" => "421321",
                "quantity" => 500,
                "expiry_date" => "2023-12-31",
                "purches_price" => 1500,
                "sale_price" => 2000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-07 10:00:00"
            ],
            [
                "name" => "Ibuprofen",
                "barcode" => "12312",
                "quantity" => 300,
                "expiry_date" => "2024-06-30",
                "purches_price" => 2500,
                "sale_price" => 3000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-07 11:00:00"
            ],
            [
                "name" => "Paracetamol",
                "barcode" => "21313",
                "quantity" => 1000,
                "expiry_date" => "2023-11-30",
                "purches_price" => 2000,
                "sale_price" => 2500,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-08 08:00:00"
            ],
            [
                "name" => "Amoxicillin",
                "barcode" => "12321312",
                "quantity" => 200,
                "expiry_date" => "2024-02-28",
                "purches_price" => 3500,
                "sale_price" => 4000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-09 09:00:00"
            ],
            [
                "name" => "Metformin",
                "barcode" => "12345",
                "quantity" => 100,
                "expiry_date" => "2024-01-31",
                "purches_price" => 4000,
                "sale_price" => 4500,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-10 10:00:00"
            ],
            [
                "name" => "Omeprazole",
                "barcode" => "67890",
                "quantity" => 200,
                "expiry_date" => "2024-03-31",
                "purches_price" => 4500,
                "sale_price" => 5000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-11 11:00:00"
            ],
            [
                "name" => "Lisinopril",
                "barcode" => "78901",
                "quantity" => 300,
                "expiry_date" => "2024-04-30",
                "purches_price" => 5000,
                "sale_price" => 5500,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-12 12:00:00"
            ],
            [
                "name" => "Atorvastatin",
                "barcode" => "5678",
                "quantity" => 400,
                "expiry_date" => "2024-05-31",
                "purches_price" => 5500,
                "sale_price" => 6000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-13 13:00:00"
            ],
            [
                "name" => "Simvastatin",
                "barcode" => "56789",
                "quantity" => 500,
                "expiry_date" => "2024-07-31",
                "purches_price" => 6000,
                "sale_price" => 6500,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-14 14:00:00"
            ],
            [
                "name" => "Amlodipine",
                "barcode" => "890",
                "quantity" => 600,
                "expiry_date" => "2024-08-31",
                "purches_price" => 6500,
                "sale_price" => 7000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-15 15:00:00"
            ],
            [
                "name" => "Losartan",
                "barcode" => "1234",
                "quantity" => 700,
                "expiry_date" => "2024-09-30",
                "purches_price" => 7000,
                "sale_price" => 7500,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-16 16:00:00"
            ],
            [
                "name" => "Levothyroxine",
                "barcode" => "89012",
                "quantity" => 800,
                "expiry_date" => "2024-10-31",
                "purches_price" => 7500,
                "sale_price" => 8000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-17 17:00:00"
            ],
            [
                "name" => "Hydrochlorothiazide",
                "barcode" => "890123",
                "quantity" => 900,
                "expiry_date" => "2024-11-30",
                "purches_price" => 8000,
                "sale_price" => 8500,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-18 18:00:00"
            ],
            [
                "name" => "Olanzapine",
                "barcode" => "8901234",
                "quantity" => 1000,
                "expiry_date" => "2024-12-31",
                "purches_price" => 8500,
                "sale_price" => 9000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-19 19:00:00"
            ],
            [
                "name" => "Metformin",
                "barcode" => "5612345",
                "quantity" => 1100,
                "expiry_date" => "2025-01-31",
                "purches_price" => 9000,
                "sale_price" => 9500,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-20 20:00:00"
            ],
            [
                "name" => "Omeprazole",
                "barcode" => "6783456",
                "quantity" => 1200,
                "expiry_date" => "2025-02-28",
                "purches_price" => 9500,
                "sale_price" => 10000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-21 21:00:00"
            ],
            [
                "name" => "Lisinopril",
                "barcode" => "7894567",
                "quantity" => 1300,
                "expiry_date" => "2025-03-31",
                "purches_price" => 10000,
                "sale_price" => 10500,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-22 22:00:00"
            ],
            [
                "name" => "Atorvastatin",
                "barcode" => "8901678",
                "quantity" => 1400,
                "expiry_date" => "2025-04-30",
                "purches_price" => 10500,
                "sale_price" => 11000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-23 23:00:00"
            ],
            [
                "name" => "Simvastatin",
                "barcode" => "9056789",
                "quantity" => 1500,
                "expiry_date" => "2025-05-31",
                "purches_price" => 11000,
                "sale_price" => 11500,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-24 00:00:00"
            ],
            [
                "name" => "Amlodipine",
                "barcode" => "01267890",
                "quantity" => 1600,
                "expiry_date" => "2025-06-30",
                "purches_price" => 11500,
                "sale_price" => 12000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-25 01:00:00"
            ],
            [
                "name" => "Lamotrigine",
                "barcode" => "12348901",
                "quantity" => 1700,
                "expiry_date" => "2025-07-31",
                "purches_price" => 1000,
                "sale_price" => 2000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-26 02:00:00"
            ],
            [
                "name" => "Levothyroxine",
                "barcode" => "23459012",
                "quantity" => 1800,
                "expiry_date" => "2025-08-31",
                "purches_price" => 2000,
                "sale_price" => 3000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-27 03:00:00"
            ],
            [
                "name" => "Oxycodone",
                "barcode" => "34560123",
                "quantity" => 1900,
                "expiry_date" => "2025-09-30",
                "purches_price" => 3000,
                "sale_price" => 4000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-28 04:00:00"
            ],
            [
                "name" => "Alprazolam",
                "barcode" => "45901234",
                "quantity" => 2000,
                "expiry_date" => "2025-10-31",
                "purches_price" => 4000,
                "sale_price" => 5000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-29 05:00:00"
            ],
            [
                "name" => "Metformin",
                "barcode" => "56712345",
                "quantity" => 2100,
                "expiry_date" => "2025-11-30",
                "purches_price" => 5000,
                "sale_price" => 6000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-30 06:00:00"
            ],
            [
                "name" => "Omeprazole",
                "barcode" => "678456",
                "quantity" => 2200,
                "expiry_date" => "2025-12-31",
                "purches_price" => 6000,
                "sale_price" => 7000,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                "created_at" => "2023-03-31 07:00:00"
            ],
        ];

        foreach ($medicines as $medicine) {
            $product = Products::create($medicine);
            $product->save();
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
