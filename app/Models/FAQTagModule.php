<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class FAQTagModule extends Model
{
    use LogsActivity;

    protected $fillable = [
        'cms_department_id',
        'faq_tag_name',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('FAQ Tag')
            ->setDescriptionForEvent(fn (string $event) => "FAQ Tag has been {$event}")
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships
    public function faqs()
    {
        return $this->hasMany(FAQModule::class, 'faq_tag_id');
    }

    public function department()
    {

        return $this->belongsTo(DepartmentModule::class, 'cms_department_id');

    }
}
