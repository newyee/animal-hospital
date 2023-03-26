<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PetType;
use Illuminate\Database\Seeder;

class PetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PetType::create([
            'name' => 'イヌ',
            'type_id' => 1
        ]);
        PetType::create([
            'name' => 'ネコ',
            'type_id' => 2
        ]);
        PetType::create([
            'name' => 'ウサギ',
            'type_id' => 3
        ]);
        PetType::create([
            'name' => 'ハムスター',
            'type_id' => 4
        ]);
        PetType::create([
            'name' => 'フェレット',
            'type_id' => 5
        ]);
        PetType::create([
            'name' => 'モルモット',
            'type_id' => 6
        ]);
    }
}
