<?php

namespace Database\Factories;

use App\Models\Ad;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdFactory extends Factory
{
    protected $model = Ad::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->randomFloat(2, 10, 10000),
            'address' => $this->faker->address(),
            'is_completed' => false,
            'views' => $this->faker->numberBetween(0, 1000),
            'photo' => 'storage/ads/placeholder.jpg'
        ];
    }
}
