<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TimeZone;
use Illuminate\Database\Seeder;


class TimeZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        TimeZone::create([
            'section' => '9:00 ~ 9:30',
            'time_zone_id' => 1
        ]);
        TimeZone::create([
            'section' => '9:30 ~ 10:00',
            'time_zone_id' => 2
        ]);
        TimeZone::create([
            'section' => '10:00 ~ 10:30',
            'time_zone_id' => 3
        ]);
        TimeZone::create([
            'section' => '10:30 ~ 11:00',
            'time_zone_id' => 4
        ]);
        TimeZone::create([
            'section' => '11:00 ~ 11:30',
            'time_zone_id' => 5
        ]);
        TimeZone::create([
            'section' => '11:30 ~ 12:00',
            'time_zone_id' => 6
        ]);
        TimeZone::create([
            'section' => '16:00 ~ 16:30',
            'time_zone_id' => 7
        ]);
        TimeZone::create([
            'section' => '16:30 ~ 17:00',
            'time_zone_id' => 8
        ]);
        TimeZone::create([
            'section' => '17:00 ~ 17:30',
            'time_zone_id' => 9
        ]);
        TimeZone::create([
            'section' => '17:30 ~ 18:00',
            'time_zone_id' => 10
        ]);
        TimeZone::create([
            'section' => '18:00 ~ 18:30',
            'time_zone_id' => 11
        ]);
        TimeZone::create([
            'section' => '18:30 ~ 19:00',
            'time_zone_id' => 12
        ]);
    }
}
