<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PetType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PetType>
 */
class PetTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            PetType::create([
                'name' => 'ネコ',
            ]),
            PetType::create([
                'name' => 'イヌ',
            ]),
            PetType::create([
                'name' => 'ウサギ',
            ]),
            PetType::create([
                'name' => 'ハムスター',
            ]),
            PetType::create([
                'name' => 'フェレット',
            ]),
            PetType::create([
                'name' => 'モルモット',
            ])
        ];
    }
}
