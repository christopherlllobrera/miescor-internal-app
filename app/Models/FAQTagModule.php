<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property int|null $cms_department_id
 * @property string|null $faq_tag_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, FAQModule> $faqs
 * @property-read DepartmentModule|null $department
 */
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
            ->dontLogEmptyChanges();
    }

    // Relationships
    /**
     * @return HasMany<FAQModule, $this>
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(FAQModule::class, 'faq_tag_id');
    }

    /**
     * @return BelongsTo<DepartmentModule, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(DepartmentModule::class, 'cms_department_id');
    }
}
