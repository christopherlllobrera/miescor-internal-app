<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $LocNo
 * @property string|null $LocCode
 * @property string|null $LocDesc
 */
class Location extends Model
{
    protected $table = 'tblLocation';

    protected $primaryKey = 'LocNo';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'LocNo',
        'LocCode',
        'LocDesc',
        'CreatedBy',
        'DateCreated',
        'UpdatedBy',
        'DateUpdated',
    ];
}
