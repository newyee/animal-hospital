<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reservation\CancelRequest;
use App\Mail\ReservationCancellationNotification;
use App\Models\ConsultationReservation;
use App\Models\ReservationCancel;
use App\Models\TimeZone;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReserveCancelController extends Controller
{
    public function cancelInfo(Request $request): JsonResponse
    {
        $reservation = ConsultationReservation::where('token', $request->token)->first();
        if (!$reservation) {
            return response()->json([], JsonResponse::HTTP_NOT_FOUND);
        }
        $displayDate = $reservation->getExpiresAtAttribute($reservation->date);
        $medicalType = $reservation->medicalType->item;
        if ($reservation->revoked) {
            return response()->json(['data' => ['date' => $displayDate, 'medicalType' => $medicalType], 'cancelled' => true], 200);
        }
        return response()->json(['data' => ['date' => $displayDate, 'medicalType' => $medicalType]]);
    }

    public function cancel(CancelRequest $request): JsonResponse
    {
        $reservation = ConsultationReservation::where('token', $request->token)->first();
        if (!$reservation) {
            return response()->json([], JsonResponse::HTTP_NOT_FOUND);
        }
        DB::beginTransaction();
        try {
            $reservationCancel = ReservationCancel::create([
                'cancellation_reason' => $request->cancellation_reason,
                'reservation_id' => $reservation->id,
            ]);
            $reservationCancel->save();
            $reservation->revoked = true;
            $reservation->save();
            $displayDate = $reservation->getExpiresAtAttribute($reservation->date);
            $pet = $reservation->pet;
            Mail::to($reservation->mail_address)->send(new ReservationCancellationNotification($pet, $displayDate,));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([], 500);
        }
        $medicalType = $reservation->medicalType->item;
        return response()->json(['data' => ['date' => $displayDate, 'medicalType' => $medicalType]]);
    }
}
