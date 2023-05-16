<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
            'View Category',
            'View Supplier',
            'View Barcode',
            'View Setting',
            'View Log',
            'View Customer',
            'Delete DebtSale',
            'Delete Product',
            'Delete User',
            'Delete Category',
            'Delete Supplier',
            'Delete Barcode',
            'Delete Logs',
            'Delete Customer',
            'Insert Sales',
            'Insert Product',
            'Insert User',
            'Insert Category',
            'Insert Supplier',
            'Insert Barcode',
            'Insert Customer',
            'Update DebtSale',
            'Update Product',
            'Update User',
            'Update Category',
            'Update Supplier',
            'Update Barcode',
            'Update Customer',
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
                'name' => 'danaz',
                'username' => 'danaz',
                'phone' => Str::limit(rand(1, 9999999999), 11, ''),
                'email' => 'danaz@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
            [
                'name' => 'helin',
                'username' => 'helin',
                'phone' => Str::limit(rand(1, 99999999999), 11, ''),
                'email' => 'helin@gmail.com',
                'password' => Hash::make('muhammad'),
                'status' => 1,
            ],
        ];

        foreach ($users as $user) {
            $users = \App\Models\User::create($user + [
                'created_at' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ]);
            $users->user_details()->create([
                'address' => fake()->randomElement(['ranya', 'Sulimany', 'Hawler', 'Halabja']),
            ]);
        }

        foreach (range(1, 10) as $index) {
            $name = fake()->firstName . ' ' . fake()->lastName;
            $name = Str::replace(' ', '', $name);
            $name = Str::replace('-', '', $name);
            $name = Str::replace('.', '', $name);
            $name = Str::limit($name, 15, '');

            $phone = fake()->phoneNumber;
            $phone  = Str::remove('+', $phone);
            $phone  = Str::remove(' ', $phone);
            $phone  = Str::remove('-', $phone);
            $phone = Str::limit($phone, 12, '');
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

            $user->role_id = json_encode([fake()->numberBetween(1, count(Role::all()))]);
            $user->saveQuietly();
        }

        // give permission for all user
        $users = User::get();
        $roles = Role::all();
        $role_id = [];
        foreach ($roles as $role) {
            $role_id[] = $role->id;
        }
        foreach ($users as $user) {
            $user->role_id = json_encode($role_id);
            $user->save();
        }
    }
}
