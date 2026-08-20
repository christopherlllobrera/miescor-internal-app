<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'button_text',
        'button_link',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active carousels
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    public function getImageUrlAttribute(): string
    {
        if (! empty($this->image) && is_string($this->image)) {
            // Check if it's binary blob data (not a plain filename)
            if (! ctype_print($this->image) || strlen($this->image) > 255) {
                $mime = 'image/jpeg'; // or detect with finfo

                return 'data:'.$mime.';base64,'.base64_encode($this->image);
            }

            // Plain filename fallback
            return asset('images/'.$this->image);
        }

        // SVG fallback...
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 400" fill="none">'
            .'<rect width="800" height="400" fill="#374151"/>'
            .'</svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
}
