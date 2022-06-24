<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
    	return [
    	    'title' => $this->faker->sentence,
            'description'=>$this->faker->paragraph,
            'image'=>$this->faker->sentence,
            'price'=>$this->faker->randomFloat(2, 0, 10000)
    	];
    }
}
