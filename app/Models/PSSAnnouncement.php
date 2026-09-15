<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PSSAnnouncement extends Model
{
    protected $fillable = [
        'announcement',
        'date_published',
        'ends_date',
        'type',
    ];
}
