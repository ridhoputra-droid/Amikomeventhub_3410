<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    protected $model = \App\Models\Partners::class;

    public function definition(): array
    {
        return [
            'name'         => fake()->company(),
            'type'         => fake()->randomElement(['sponsor', 'media', 'vendor']),
            'logo_path'    => null,
            'description'  => fake()->sentence(),
            'contact_email'=> fake()->optional()->email(),
        ];
    }
}
