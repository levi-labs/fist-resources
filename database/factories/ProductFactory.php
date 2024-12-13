<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),  // Menghasilkan kata acak sebagai nama produk
            'description' => $this->faker->paragraph(),  // Menghasilkan paragraf acak untuk deskripsi produk
            'price' => $this->faker->randomFloat(2, 10, 1000),  // Menghasilkan harga acak antara 10 dan 1000 dengan 2 desimal
            'sku' => $this->faker->randomNumber(6),  // Menghasilkan jumlah produk antara 1 dan 100
            'image' => $this->faker->imageUrl(640, 480, 'products'),  // Menghasilkan URL gambar produk
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
