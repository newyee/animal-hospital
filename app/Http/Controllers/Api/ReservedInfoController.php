<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\ReservationResource;
use App\Models\ConsultationReservation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservedInfoController extends Controller
{
    //
    public function reserveList(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $reservations = ConsultationReservation::where('user_id', $userId)
            ->with(['timeZone', 'medicalType', 'pet'])
            ->get();

        $formattedReservations = ReservationResource::collection($reservations);
        return response()->json($formattedReservations);
    }

    public function reservedTimeList(Request $request): JsonResponse
    {
        // 今月 ~ 2ヶ月後までの予約データを取得
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->addMonths(2)->endOfMonth();

        $data = ConsultationReservation::whereBetween('date', [$start, $end])->where('revoked', false)->get();
        $formattedData = $data->groupBy('date')->map(function ($reservations) {
            return [
                'time_zone_ids' => $reservations->pluck('time_zone_id')
            ];
        });
        return response()->json($formattedData);
    }
}
