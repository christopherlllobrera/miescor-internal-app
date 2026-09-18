<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $contract_id
 * @property int $user_id
 * @property string $remark
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Contract|null $contract
 * @property-read User|null $user
 */
class ContractRemark extends Model
{
    protected $fillable = ['contract_id', 'user_id', 'remark'];

    /**
     * @return BelongsTo<Contract, $this>
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
