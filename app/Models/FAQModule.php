<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property int|null $cms_department_id
 * @property int|null $faq_tag_id
 * @property string|null $faq_title
 * @property string|null $faq_slug
 * @property string|null $faq_body
 * @property bool $faq_is_published
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DepartmentModule|null $department
 * @property-read FAQTagModule|null $tag
 */
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
            ->dontLogEmptyChanges();
    }

    // Relationships
    /**
     * @return BelongsTo<DepartmentModule, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(DepartmentModule::class, 'cms_department_id');
    }

    /**
     * @return BelongsTo<FAQTagModule, $this>
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(FAQTagModule::class, 'faq_tag_id');
    }
}
