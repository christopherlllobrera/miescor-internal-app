<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceAuthItem extends Model
{
    protected $fillable = [
        'attendance_auth_id',
        'date',
        'time_in',
        'time_out',
        'request_time_in',
        'request_time_out',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function attendanceAuth(): BelongsTo
    {
        return $this->belongsTo(AttendanceAuth::class, 'attendance_auth_id');
    }
}
