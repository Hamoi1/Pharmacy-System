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
        $catagorys = [
            "Anesthesiology",
            "Cardiology",
            "Dermatology",
            "Endocrinology",
            "Gastroenterology",
            "Geriatrics",
            "Infectious disease",
            "Internal medicine",
            "Medical genetics",
            "Nephrology",
            "Neurology",
            "Obstetrics", "gynecology",
            "Oncology",
            "Ophthalmology",
            "Orthopedics",
            "Otolaryngology",
            "Pathology",
            "Pediatrics",
            "Physical medicine", "rehabilitation",
            "Psychiatry",
            "Pulmonology",
            "Radiology",
            "Rheumatology",
            "Sports medicine",
            "Surgery",
            "Allergy", "Immunology",
            "Bariatric Medicine",
            "Behavioral Health",
            "Cardiovascular Disease",
            "Clinical Genetics",
            "Clinical Neurophysiology",
            "Colon", "Rectal Surgery",
            "Critical Care Medicine",
            "Emergency Medicine",
            "Environmental Medicine",
            "Family Medicine",
            "Forensic Medicine",
            "Hematology",
            "Hospital Medicine",
            "Infectious Diseases",
            "Integrative Medicine",
            "Medical Toxicology",
            "Neonatal-erinatal", "Medicine",
            "Pain Medicine",
            "Palliative Care",
            "Sleep Medicine",
            "Transplant Hepatology",
        ];
        foreach ($catagorys as $category) {
            \App\Models\Categorys::create([
                'name' => $category,
                'slug' => Str::slug($category),
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
        $this->call(UsersSeeder::class);
        $this->call(ProductsSeeder::class);
    }
}
