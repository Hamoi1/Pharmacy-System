<?php

namespace Database\Seeders;

use App\Models\Categorys;
use App\Models\ProductsQuantity;
use App\Models\Suppliers;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $medicines = [
            "Acetaminophen",
            "Albuterol",
            "Alprazolam",
            "Amoxicillin",
            "Atorvastatin",
            "Azithromycin",
            "Baclofen",
            "Betamethasone",
            "Budesonide",
            "Bupropion",
            "Calcium carbonate",
            "Carbamazepine",
            "Celecoxib",
            "Chlorothiazide",
            "Clonazepam",
            "Clopidogrel",
            "Codeine",
            "Cromolyn sodium",
            "Cymbalta",
            "Digoxin",
            "Diphenhydramine",
            "Docusate sodium",
            "Donepezil",
            "Fluconazole",
            "Fluticasone",
            "Fluvoxamine",
            "Gabapentin",
            "Gemfibrozil",
            "Glyburide",
            "Hydrochlorothiazide",
            "Ibuprofen",
            "Insulin",
            "Lisinopril",
            "Losartan",
            "Magnesium sulfate",
            "Meclizine",
            "Metformin",
            "Metoprolol",
            "Morphine",
            "Naproxen",
            "Omeprazole",
            "Pantoprazole",
            "Paroxetine",
            "Prednisone",
            "Propranolol",
            "Quetiapine",
            "Ramipril",
            "Risperidone",
            "Salmeterol",
            "Sertraline",
            "Sildenafil",
            "Sodium bicarbonate",
            "Spironolactone",
            "Tamsulosin",
            "Temazepam",
            "Terbutaline",
            "Theophylline",
            "Tramadol",
            "Trimethoprim",
            "Valsartan",
            "Venlafaxine",
            "Warfarin",
            "Xanax",
            "Zoloft"
        ];
        $id = 1;
        foreach ($medicines as $product) {
            $quantity = rand(1, 1000);
            $sale_price = fake()->randomFloat(2, 1, 1000);
            $purches_price = fake()->randomFloat(2, 1, 1000);
            $expiry_date = fake()->dateTimeBetween('now', '+3 years');
            \App\Models\Products::create([
                'name' => $product,
                'barcode' => fake()->ean13,
                'quantity' => $quantity,
                'sale_price' => $sale_price,
                'purches_price' => $purches_price,
                'expiry_date' => $expiry_date,
                'category_id' => Categorys::inRandomOrder()->first()->id,
                'supplier_id' => Suppliers::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            ProductsQuantity::create([
                'product_id' =>$id++,
                'quantity' => $quantity,
                'sale_price' => $sale_price,
                'purches_price' => $purches_price,
                'expiry_date' => $expiry_date,
                'quantity' => $quantity,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
