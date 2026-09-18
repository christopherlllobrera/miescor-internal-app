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
 * @property string|null $poc_name_id
 * @property string|null $poc_job_position
 * @property string|null $poc_image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $full_name
 * @property-read string $image_url
 * @property-read DepartmentModule|null $department
 * @property-read Position|null $position
 * @property-read Employee|null $employee
 */
class DirectoryModule extends Model
{
    use LogsActivity;

    protected $fillable = [
        'cms_department_id',
        'poc_name_id',
        'poc_job_position',
        'poc_image',
    ];

    // Activity Logs
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Directories')
            ->setDescriptionForEvent(fn (string $event) => "Directory has been {$event}")
            ->logOnly(['poc_name_id', 'poc_job_position', 'created_at', 'updated_at'])
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
     * @return BelongsTo<Position, $this>
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'poc_job_position');
    }

    /**
     * @return BelongsTo<Employee, $this>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'poc_name_id', 'EmpNo');
    }

    public function getFullNameAttribute(): string
    {
        return $this->employee->full_name ?? '';
    }

    public function setPocImageAttribute(mixed $value): void
    {
        $this->attributes['poc_image'] = $value;
    }

    public function getImageUrlAttribute(): string
    {
        if (! empty($this->poc_image)) {
            return 'data:image/jpeg;base64,'.base64_encode($this->poc_image);
        }

        return asset('images/placeholder.jpg');
    }
}
