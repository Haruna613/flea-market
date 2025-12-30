<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;
use App\Models\Condition;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
{
    return [
        'user_id' => \App\Models\User::factory(),
        'name' => $this->faker->word,
        'brand_name' => $this->faker->company,
        'price' => $this->faker->numberBetween(100, 10000),
        'description' => $this->faker->sentence,
        'condition_id' => \App\Models\Condition::factory(),
        'image_path' => 'item_images/dummy_item.png',
    ];
}
}
