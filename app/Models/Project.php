<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $ProjectNo
 * @property string|null $ProjectDesc
 * @property int|null $BUNo
 * @property string|null $CostCntrNo
 * @property int|null $ClientID
 * @property int|null $CreatedBy
 * @property int|null $UpdatedBy
 * @property Carbon|null $DateCreated
 * @property Carbon|null $DateUpdated
 */
class Project extends Model
{
    protected $table = 'tblProject';

    protected $primaryKey = 'ProjectNo';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'ProjectNo',
        'ProjectDesc',
        'BUNo',
        'CostCntrNo',
        'ClientID',
        'CreatedBy',
        'DateCreated',
        'UpdatedBy',
        'DateUpdated',
    ];

    protected $casts = [
        'DateCreated' => 'datetime',
        'DateUpdated' => 'datetime',
    ];
}
