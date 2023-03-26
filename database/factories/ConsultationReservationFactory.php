<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MedicalType;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConsultationReservation>
 */
class ConsultationReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $date = fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d');
        $time_zone_id = fake()->unique()->numberBetween(1, 12);
        $timeZoneStartTimes = config('timezone.start_times');
        $endTimeWithDate = $date . ' ' . $timeZoneStartTimes[$time_zone_id];
        $endTimeWithDate = Carbon::createFromFormat('Y-m-d H:i', $endTimeWithDate, 'Asia/Tokyo');
        $endTimeWithDate = $endTimeWithDate->format('Y-m-d H:i:s');
        $token = Str::random(32);

        return [
            //
            'visit' => fake()->randomElement([1, 2]),
            'date' => $date,
            'owner_name' => $user->name,
            'mail_address' => $user->email,
            'phone_number' => fake()->phoneNumber(),
            'remark' => fake()->text('200'),
            'medical_type_id' => MedicalType::all()->random()->id,
            'user_id' => $user->id,
            'time_zone_id' => $time_zone_id,
            'pet_id' => fake()->randomElement([Pet::factory()]),
            'token' => $token,
            'expires_at' => $endTimeWithDate,
        ];
    }
}
