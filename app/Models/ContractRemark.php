<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractRemark extends Model
{
    protected $fillable = ['contract_id', 'user_id', 'remark'];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
