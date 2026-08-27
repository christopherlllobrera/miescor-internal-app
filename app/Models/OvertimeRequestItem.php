<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OvertimeRequestItem extends Model
{
    protected $fillable = [
        'overtime_request_id',
        'date',
        'ot_start',
        'ot_end',
        'number_of_hours',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function overtimeRequest(): BelongsTo
    {
        return $this->belongsTo(OvertimeRequest::class, 'overtime_request_id');
    }
}
