<?php

namespace Database\Seeders;

use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::insert(
            [
                ['name' => "Pcs", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => "Pil", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => "Botol", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => "Box", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => "Dus", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => "Kapsul", 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]
        );
    }
}
