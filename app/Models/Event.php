<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'color',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('F j, Y');
    }

    /**
     * Get dot color class
     */
    public function getDotColorAttribute(): string
    {
        $colors = [
            'orange' => 'bg-orange-600',
            'violet' => 'bg-violet-600',
            'pink' => 'bg-pink-600',
            'blue' => 'bg-blue-600',
            'green' => 'bg-green-600',
            'red' => 'bg-red-600',
            'black' => 'bg-black',
        ];

        return $colors[$this->color] ?? 'bg-orange-600';
    }
}
