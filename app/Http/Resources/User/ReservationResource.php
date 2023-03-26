<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $timeZone = $this->timeZone->section;
        $medicalType = $this->medicalType->item;
        $petName = $this->pet->name;
        $displayDate = $this->getExpiresAtAttribute($this->date);
        return [
            'medicalType' => $medicalType,
            'petName' => $petName,
            'date' => $displayDate,
        ];
    }
}
