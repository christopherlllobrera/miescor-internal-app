<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $attendance_auth_id
 * @property Carbon|null $date
 * @property string|null $time_in
 * @property string|null $time_out
 * @property string|null $request_time_in
 * @property string|null $request_time_out
 * @property string|null $remarks
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
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
