<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            PetTypeSeeder::class,
            MedicalTypeSeeder::class,
            PetSeeder::class,
            TimeZoneSeeder::class,
            ConsultationReservationSeeder::class,
        ]);
    }
}
