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
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function attendanceAuth(): BelongsTo
    {
        return $this->belongsTo(AttendanceAuth::class, 'attendance_auth_id');
    }
}
