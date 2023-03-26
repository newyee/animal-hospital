<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationCancel extends Model
{
    use HasFactory;
    protected $fillable = [
        'cancellation_reason',
        'reservation_id',
    ];
}
