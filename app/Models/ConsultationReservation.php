<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ConsultationReservation extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string
     */
    protected $fillable = [
        'visit',
        'medical_type_id',
        'pet_type_id',
        'time_zone_id',
        'date',
        'patient_ticket_number',
        'owner_name',
        'pet_name',
        'phone_number',
        'mail_address',
        'remark',
        'user_id',
        'pet_id',
        'token',
        'expires_at',
        'revoked',
    ];

    public function timeZone()
    {
        return $this->belongsTo(TimeZone::class);
    }

    public function medicalType()
    {
        return $this->belongsTo(MedicalType::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function getExpiresAtAttribute($value)
    {
        $carbonDate = Carbon::parse($value);
        $formattedDate = $carbonDate->isoFormat("MM月DD日(dddd)");
        $timeZone = TimeZone::where('time_zone_id', $this->time_zone_id)->first();
        $displayDate = $formattedDate . ' ' . $timeZone->section;
        return $displayDate;
    }
}
