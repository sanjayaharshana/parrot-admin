<?php

namespace Modules\UserPanel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TVShowsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\UserPanel\Models\TVShows::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

