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
        // $roles = [
        //     'View Dashboard',
        //     'View Sales',
        //     'View DebtSale',
        //     'View Product',
        //     'View User',
        //     'View Category',
        //     'View Supplier',
        //     'View Barcode',
        //     'View Setting',
        //     'View Log',
        //     'View Customer',
        //     'Delete DebtSale',
        //     'Delete Product',
        //     'Delete User',
        //     'Delete Category',
        //     'Delete Supplier',
        //     'Delete Barcode',
        //     'Delete Logs',
        //     'Delete Customer',
        //     'Insert Sales',
        //     'Insert Product',
        //     'Insert User',
        //     'Insert Category',
        //     'Insert Supplier',
        //     'Insert Barcode',
        //     'Insert Customer',
        //     'Update DebtSale',
        //     'Update Product',
        //     'Update User',
        //     'Update Category',
        //     'Update Supplier',
        //     'Update Barcode',
        //     'Update Customer',
        //     'Product Trash',
        //     'User Trash',
        //     'Category Trash',
        //     'Supplier Trash',
        //     'Clear Log',
        //     'User GenerateReport',
        //     'User Export',
        //     'Product Export'
        // ];

        // foreach ($roles as $role) {
        //     Role::insert([
        //         'name' => $role
        //     ]);
        // }

        // $users = [
        //     [
        //         'name' => 'muhammad',
        //         'username' => 'hama',
        //         'phone' => Str::limit(rand(1, 99999999999), 11, ''),
        //         'email' => 'ihama9728@gmail.com',
        //         'password' => Hash::make('muhammad'),
        //         'status' => 1,
        //     ],
        //     [
        //         'name' => 'hogr',
        //         'username' => 'hogr',
        //         'phone' => Str::limit(rand(1, 99999999999), 11, ''),
        //         'email' => 'hogr@gmail.com',
        //         'password' => Hash::make('muhammad'),
        //         'status' => 1,
        //     ],
        //     [
        //         'name' => 'aven',
        //         'username' => 'aven',
        //         'phone' => Str::limit(rand(1, 9999999999), 11, ''),
        //         'email' => 'aven@gmail.com',
        //         'password' => Hash::make('muhammad'),
        //         'status' => 1,
        //     ],
        //     [
        //         'name' => 'evan',
        //         'username' => 'evan',
        //         'phone' => Str::limit(rand(1, 9999999999), 11, ''),
        //         'email' => 'evan@gmail.com',
        //         'password' => Hash::make('muhammad'),
        //         'status' => 1,
        //     ],
        //     [
        //         'name' => 'chra',
        //         'username' => 'chra',
        //         'phone' => Str::limit(rand(1, 9999999999), 11, ''),
        //         'email' => 'chra@gmail.com',
        //         'password' => Hash::make('muhammad'),
        //         'status' => 1,
        //     ],
        //     [
        //         'name' => 'redin',
        //         'username' => 'redin',
        //         'phone' => Str::limit(rand(1, 9999999999), 11, ''),
        //         'email' => 'redin@gmail.com',
        //         'password' => Hash::make('muhammad'),
        //         'status' => 1,
        //     ],
        //     [
        //         'name' => 'gashtyar',
        //         'username' => 'gashtyar',
        //         'phone' => Str::limit(rand(1, 9999999999), 11, ''),
        //         'email' => 'gashtyar@gmail.com',
        //         'password' => Hash::make('muhammad'),
        //         'status' => 1,
        //     ],
        //     [
        //         'name' => 'danaz',
        //         'username' => 'danaz',
        //         'phone' => Str::limit(rand(1, 9999999999), 11, ''),
        //         'email' => 'danaz@gmail.com',
        //         'password' => Hash::make('muhammad'),
        //         'status' => 1,
        //     ],
        //     [
        //         'name' => 'rekar',
        //         'username' => 'rekar',
        //         'phone' => Str::limit(rand(1, 99999999999), 11, ''),
        //         'email' => 'rekar@gmail.com',
        //         'password' => Hash::make('muhammad'),
        //         'status' => 1,
        //     ],
        //     [
        //         'name' => 'akar',
        //         'username' => 'akar',
        //         'phone' => Str::limit(rand(1, 99999999999), 11, ''),
        //         'email' => 'akar@gmail.com',
        //         'password' => Hash::make('muhammad'),
        //         'status' => 1,
        //     ],
        // ];

        // foreach ($users as $user) {
        //     $users = \App\Models\User::create($user + [
        //         'created_at' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
        //     ]);
        //     $users->user_details()->create([
        //         'address' => fake()->randomElement(['ranya', 'Sulimany', 'Hawler', 'Halabja']),
        //     ]);
        // }
     

        // give permission for all user
        // $users = User::take(11)->get();
        // $roles = Role::all();
        // $role_id = [];
        // foreach ($roles as $role) {
        //     $role_id[] = $role->id;
        // }

        // foreach ($users as $user) {
        //     $user->role_id = json_encode($role_id);
        //     $user->save();
        // }
           foreach (range(1, 1100) as $index) {
            $name = fake()->name;
            $name = Str::replace(' ', '', $name);
            $name = Str::replace('-', '', $name);
            $name = Str::replace('.', '', $name);
            $name = Str::limit($name, 7, '');

            $phone = fake()->phoneNumber;
            $phone  = Str::remove('+', $phone);
            $phone  = Str::remove(' ', $phone);
            $phone  = Str::remove('-', $phone);
            $phone = Str::limit($phone, 11, '');
            $user = \App\Models\User::create([
                'name' => $name,
                'username' => fake()->unique()->userName . '-' . $index . $name,
                'phone' => $phone,
                'email' => fake()->unique()->safeEmail,
                'password' => Hash::make('muhammad'),
                'status' => fake()->numberBetween(0, 1),
                'created_at' => fake()->randomElement([fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'), 'now'])
            ]);
            $user->user_details()->create([
                'address' => fake()->randomElement(['ranya', 'sulimany', 'qaladzi', 'hawler', 'Hallshow']),
            ]);
        }
        // $catagorys_data = [
        //     [
        //         'name' => 'darzi',
        //         'slug' => Str::slug('darzi'),
        //     ],
        //     [
        //         'name' => 'shrubi mndallan',
        //         'slug' => Str::slug('shrubi mndallan'),
        //     ],
        //     [
        //         'name' => '3abi saresha',
        //         'slug' => Str::slug('3abi saresha'),
        //     ],
        //     [
        //         'name' => 'shrubi zghesha',
        //         'slug' => Str::slug('shrubi zghesha'),
        //     ],
        //     [
        //         'name' => 'mal3ami dmwchaw',
        //         'slug' => Str::slug('mal3ami dmwchaw'),
        //     ],
        //     [
        //         'name' => 'shrub',
        //         'slug' => Str::slug('shrub'),
        //     ],
        //     [
        //         'name' => '3ab',
        //         'slug' => Str::slug('3ab'),
        //     ],
        //     [
        //         'name' => 'krim',
        //         'slug' => Str::slug('krim'),
        //     ],
        //     [
        //         'name' => 'qatra',
        //         'slug' => Str::slug('qatra'),
        //     ],
        //     [
        //         'name' => 'mal3am',
        //         'slug' => Str::slug('mal3am'),
        //     ],
        // ];
        // foreach ($catagorys_data as $category) {
        //     \App\Models\Categorys::create($category, [
        //         'created_at' => fake()->dateTimeBetween('-1 years', 'now'),
        //     ]);
        // }
        // $suppliers = [
        //     [
        //         'name' => 'gilas',
        //         'phone' => '07501122110',
        //         'email' => 'gilas@gmail.com',
        //         'address' => 'iraq'
        //     ],
        //     [
        //         'name' => 'ahmad',
        //         'phone' => '07501122111',
        //         'email' => 'ahmad@gmail.com',
        //         'address' => 'iraq'
        //     ],
        //     [
        //         'name' => 'heshu',
        //         'phone' => '07502122110',
        //         'email' => 'heshu@gmail.com',
        //         'address' => 'iraq'
        //     ],
        //     [
        //         'name' => 'razhan',
        //         'phone' => '07502122232',
        //         'email' => 'razhan@gmail.com',
        //         'address' => 'iraq'
        //     ],
        //     [
        //         'name' => 'muhammad',
        //         'phone' => '075022232',
        //         'email' => 'muhammad@gmail.com',
        //         'address' => 'iraq'
        //     ],
        //     [
        //         'name' => 'ramyar',
        //         'phone' => '07512122232',
        //         'email' => 'ramyar@gmail.com',
        //         'address' => 'iraq'
        //     ],
        //     [
        //         'name' => 'mihraban',
        //         'phone' => '07511212232',
        //         'email' => 'mihraban@gmail.com',
        //         'address' => 'iraq'
        //     ],
        //     [
        //         'name' => 'danaz',
        //         'phone' => '07511222232',
        //         'email' => 'danaz@gmail.com',
        //         'address' => 'iraq'
        //     ],
        //     [
        //         'name' => 'sazyar',
        //         'phone' => '07511222232',
        //         'email' => 'sazyar@gmail.com',
        //         'address' => 'iraq'
        //     ],
        //     [
        //         'name' => 'savyar',
        //         'phone' => '07511222232',
        //         'email' => 'savyar@gmail.com',
        //         'address' => 'iraq'
        //     ],
        // ];
        // foreach ($suppliers as $supplier) {
        //     \App\Models\Suppliers::create($supplier, [
        //         'created_at' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
        //     ]);
        // }
        // $barcode = 1;
        // foreach (range(1, 100000) as $index) {
        //     // create a product
        //     Products::create([
        //         "name" => fake()->name(),
        //         "barcode" => $barcode++,
        //         "quantity" => fake()->randomNumber(),
        //         "expiry_date" => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
        //         "purches_price" => 1500,
        //         "sale_price" => 2000,
        //         'category_id' => Categorys::inRandomOrder()->first()->id,
        //         'supplier_id' => Suppliers::inRandomOrder()->first()->id,
        //         'user_id' => User::inRandomOrder()->first()->id,
        //         "created_at" => "2023-03-07 10:00:00"
        //     ]);
        // }
        // $medicines = [
        //     [
        //         "name" => "Aspirin",
        //         "barcode" => "421321",
        //         "quantity" => 500,
        //         "expiry_date" => "2023-12-31",
        //         "purches_price" => 1.5,
        //         "sale_price" => 2,
        //         'category_id' => Categorys::inRandomOrder()->first()->id,
        //         'supplier_id' => Suppliers::inRandomOrder()->first()->id,
        //         'user_id' => User::inRandomOrder()->first()->id,
        //         "created_at" => "2023-03-07 10:00:00"
        //     ],
        //     [
        //         "name" => "Ibuprofen",
        //         "barcode" => "12312",
        //         "quantity" => 300,
        //         "expiry_date" => "2024-06-30",
        //         "purches_price" => 1,
        //         "sale_price" => 2,
        //         'category_id' => Categorys::inRandomOrder()->first()->id,
        //         'supplier_id' => Suppliers::inRandomOrder()->first()->id,
        //         'user_id' => User::inRandomOrder()->first()->id,
        //         "created_at" => "2023-03-07 11:00:00"
        //     ],
        //     [
        //         "name" => "Paracetamol",
        //         "barcode" => "21313",
        //         "quantity" => 1000,
        //         "expiry_date" => "2023-11-30",
        //         "purches_price" => 0.5,
        //         "sale_price" => 1,
        //         'category_id' => Categorys::inRandomOrder()->first()->id,
        //         'supplier_id' => Suppliers::inRandomOrder()->first()->id,
        //         'user_id' => User::inRandomOrder()->first()->id,
        //         "created_at" => "2023-03-08 08:00:00"
        //     ],
        //     [
        //         "name" => "Amoxicillin",
        //         "barcode" => "12321312",
        //         "quantity" => 200,
        //         "expiry_date" => "2024-02-28",
        //         "purches_price" => 0.2,
        //         "sale_price" => 0.5,
        //         'category_id' => Categorys::inRandomOrder()->first()->id,
        //         'supplier_id' => Suppliers::inRandomOrder()->first()->id,
        //         'user_id' => User::inRandomOrder()->first()->id,
        //         "created_at" => "2023-03-09 09:00:00"
        //     ],
        //     [
        //         "name" => "Alprazolam",
        //         "barcode" => "45901234",
        //         "quantity" => 2000,
        //         "expiry_date" => "2025-10-31",
        //         "purches_price" => 2,
        //         "sale_price" => 3,
        //         'category_id' => Categorys::inRandomOrder()->first()->id,
        //         'supplier_id' => Suppliers::inRandomOrder()->first()->id,
        //         'user_id' => User::inRandomOrder()->first()->id,
        //         "created_at" => "2023-03-29 05:00:00"
        //     ],
        // ];

        // foreach ($medicines as $medicine) {
        //     $product = Products::with('product_quantity')->create($medicine);
        //     $product->product_quantity()->create([
        //         'quantity' => $product->quantity,
        //         'purches_price' => $product->purches_price,
        //         'sale_price' => $product->sale_price,
        //         'expiry_date' => $product->expiry_date,
        //         'created_at' => $product->created_at,
        //     ]);
        //     $product->save();
        // }

        // foreach (range(1, 20) as $index) {
        //     $number = fake()->unique()->numberBetween(0, 2147483647) . Str::random(1);
        //     $number = Str::limit($number, 9, '');
        //     $invoice = Str::start($number, 'inv-');
        //     $sales = Sales::create([
        //         'invoice' => $invoice,
        //         'user_id' => User::inRandomOrder()->first()->id,
        //         'total' => fake()->numberBetween(1, 1000000),
        //         'discount' => fake()->numberBetween(0, 100),
        //         'status' => 1,
        //         'created_at' => now(),
        //     ]);
        //     foreach (range(1, rand(1, 9)) as $index) {
        //         $sales->sale_details()->create([
        //             'product_id' => Products::inRandomOrder()->first()->id,
        //             'quantity' => fake()->numberBetween(1, 100),
        //             'created_at' => now(),
        //         ]);
        //     }
        // }

        // foreach (range(1, 20) as $index) {
        //     $number = fake()->unique()->numberBetween(0, 2147483647) . Str::random(1);
        //     $number = Str::limit($number, 9, '');
        //     $invoice = Str::start($number, 'inv-');
        //     $sales = Sales::create([
        //         'invoice' => $invoice,
        //         'user_id' => User::inRandomOrder()->first()->id,
        //         'total' => fake()->numberBetween(1, 1000000),
        //         'discount' => fake()->numberBetween(0, 100),
        //         'status' => 1,
        //         'paid' => 0,
        //         'created_at' => now(),
        //     ]);
        //     $sales->debt_sale()->create([
        //         'name' => fake()->name,
        //         'phone' => fake()->phoneNumber,
        //         'amount' => $sales->total,
        //         'paid' => fake()->numberBetween(0, $sales->total),
        //         'remain' => $sales->total - fake()->numberBetween(0, $sales->total),
        //         'created_at' => now(),
        //     ]);
        //     foreach (range(1, rand(1, 9)) as $index) {
        //         $sales->sale_details()->create([
        //             'product_id' => Products::inRandomOrder()->first()->id,
        //             'quantity' => fake()->numberBetween(1, 100),
        //             'created_at' => now(),
        //         ]);
        //     }
        // }
    }
}
