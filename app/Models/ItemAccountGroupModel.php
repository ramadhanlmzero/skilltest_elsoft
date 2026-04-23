<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemAccountGroupModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'item_account_groups';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ItemModel::class, 'item_account_group_id');
    }
}
