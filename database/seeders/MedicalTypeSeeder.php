<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MedicalType;
use Illuminate\Database\Seeder;

class MedicalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        MedicalType::create([
            'item' => '※犬猫以外の初診※',
            'item_id' => 1
        ]);
        MedicalType::create([
            'item' => '犬と猫の初診',
            'item_id' => 2
        ]);
        MedicalType::create([
            'item' => 'オンライン相談',
            'item_id' => 3
        ]);
        MedicalType::create([
            'item' => 'オンライン診療',
            'item_id' => 4
        ]);
        MedicalType::create([
            'item' => '※犬猫以外の動物※',
            'item_id' => 5
        ]);
        MedicalType::create([
            'item' => '予防（ワクチン・狂犬病・フィラリアなど）',
            'item_id' => 6
        ]);
        MedicalType::create([
            'item' => 'ケアのみ（爪切り・耳掃除・肛門腺など）',
            'item_id' => 7
        ]);
        MedicalType::create([
            'item' => '前回の再診',
            'item_id' => 8
        ]);
        MedicalType::create([
            'item' => '別件での診察',
            'item_id' => 9
        ]);
    }
}
