<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $table = "products";

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'barcode' => $this->faker->ean13(),
            'name' => $this->faker->word(),
            'category_id' => random_int(1, 5),
            'unit_id' => random_int(1, 6),
            'selling_price' => $this->faker->numberBetween(5000, 100000),
            'stock_limit' => $this->faker->numberBetween(10, 50),
        ];
    }
}
