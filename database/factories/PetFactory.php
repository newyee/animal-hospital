<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PetType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'patient_ticket_number' => fake()->numberBetween(1, 100000),
            'gender' => fake()->randomElement(['2', '1']),
            'pet_type_id' => PetType::all()->random()->id,
            'user_id' => fake()->randomElement([User::factory()]),
        ];
    }
}
