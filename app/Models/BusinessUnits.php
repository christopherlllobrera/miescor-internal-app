<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessUnits extends Model
{
    protected $table = 'tblBusinessUnits';

    protected $primaryKey = 'BusinessUnitNo';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'BusinessUnitNo',
        'BusinessUnitDesc',
        'CreatedBy',
        'DateCreated',
        'UpdatedBy',
        'DateUpdated',
    ];
}
