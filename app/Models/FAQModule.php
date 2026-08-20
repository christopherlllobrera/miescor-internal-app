<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class FAQModule extends Model
{
    use LogsActivity;

    protected $fillable = [
        'cms_department_id',
        'faq_tag_id',
        'faq_title',
        'faq_slug',
        'faq_body',
        'faq_is_published',
    ];

    protected $casts = [
        'faq_is_published' => 'boolean',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('FAQ Module')
            ->setDescriptionForEvent(fn (string $event) => "FAQ has been {$event}")
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function department()
    {
        return $this->belongsTo(DepartmentModule::class, 'cms_department_id');
    }

    public function tag()
    {
        return $this->belongsTo(FAQTagModule::class, 'faq_tag_id');
    }
}
