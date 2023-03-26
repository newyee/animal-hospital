<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\ConfirmRequest;
use App\Http\Requests\Api\Reservation\DecideRequest;
use App\Mail\RegisterReservation;
use App\Models\ConsultationReservation;
use App\Models\Pet;
use App\Models\TimeZone;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;


class ReserveController extends Controller
{

    public function confirm(ConfirmRequest $request): JsonResponse
    {
        $validated = collect($request->validated())->mapWithKeys(function ($value, $key) {
            return [Str::camel($key) => $value];
        })->toArray();

        return response()->json($validated);
    }

    public function decide(DecideRequest $request): JsonResponse
    {
        $timeZoneStartTimes = config('timezone.start_times');
        $endTimeWithDate = $request->date . ' ' . $timeZoneStartTimes[$request->time_zone_id];
        $endTimeWithDate = Carbon::createFromFormat('Y-m-d H:i', $endTimeWithDate, 'Asia/Tokyo');
        $endTimeWithDate = $endTimeWithDate->format('Y-m-d H:i:s');
        $token = Str::random(32);
        DB::beginTransaction();
        try {
            $pet = Pet::create([
                'name' => $request->pet_name,
                'pet_type_id' => $request->pet_type,
                'patient_ticket_number' => $request->ticket_number,
            ]);

            $reservation = ConsultationReservation::create([
                'visit' => $request->visit,
                'medical_type_id' => $request->medical_type,
                'time_zone_id' => $request->time_zone_id,
                'date' => $request->date,
                'owner_name' => $request->owner_name,
                'token' => $token,
                'expires_at' => $endTimeWithDate,
                'phone_number' => $request->phone_number,
                'mail_address' => $request->email,
                'remark' => $request->remark,
                'user_id' => $request->user_id,
                'pet_id' => $pet->id
            ]);
            $displayDate = $reservation->getExpiresAtAttribute($reservation->date);
            Mail::to($reservation->mail_address)->send(new RegisterReservation($pet, $displayDate, $reservation->token, $reservation->expires_at));
            $medicalType = $reservation->medicalType->item;
            $reservedPet =  $reservation->pet;
            $petType = $reservedPet->petType->name;
            $resData = [
                'visit' => $reservation->visit,
                'formatDate' => $displayDate,
                'medicalType' => $medicalType,
                'petType' => $petType,
                'ownerName' => $reservation->owner_name,
                'petName' => $reservedPet->name,
                'phoneNumber' => $reservation->phone_number,
                'email' => $reservation->mail_address,
                'remark' => $reservation->remark
            ];
            DB::commit();
            return response()->json($resData);
        } catch (Throwable $th) {
            //throw $th;
            DB::rollBack();
            throw $th;
        }
    }
}
