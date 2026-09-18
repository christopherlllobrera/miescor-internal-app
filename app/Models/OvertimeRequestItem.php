<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $overtime_request_id
 * @property Carbon|null $date
 * @property string|null $ot_start
 * @property string|null $ot_end
 * @property float|null $number_of_hours
 * @property string|null $reason
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class OvertimeRequestItem extends Model
{
    protected $fillable = [
        'overtime_request_id',
        'date',
        'ot_start',
        'ot_end',
        'number_of_hours',
        'reason',
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

    public function overtimeRequest(): BelongsTo
    {
        return $this->belongsTo(OvertimeRequest::class, 'overtime_request_id');
    }
}
