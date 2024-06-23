<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCategory::insert(
            [
                ['name' => "Antibiotik", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => "Multivitamin", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => "Sales", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => "Obat herbal", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => "Suplemen", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]
        );
    }
}
