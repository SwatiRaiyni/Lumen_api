<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Model::class;

    public function definition(): array
    {
    	return [
    	    'title' => $this->faker->sentence,
            'description'=>$this->faker->paragraph,
            'image'=>$this->faker->uniqid() . '.jpg',
            'price'=>$this->faker->randomFloat(2, 0, 10000)
    	];
    }
}
