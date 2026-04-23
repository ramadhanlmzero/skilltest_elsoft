<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'items';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'company_id',
        'item_type_id',
        'code',
        'label',
        'item_group_id',
        'item_account_group_id',
        'item_unit_id',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyModel::class, 'company_id');
    }

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemTypeModel::class, 'item_type_id');
    }

    public function itemGroup(): BelongsTo
    {
        return $this->belongsTo(ItemGroupModel::class, 'item_group_id');
    }

    public function itemAccountGroup(): BelongsTo
    {
        return $this->belongsTo(ItemAccountGroupModel::class, 'item_account_group_id');
    }

    public function itemUnit(): BelongsTo
    {
        return $this->belongsTo(ItemUnitModel::class, 'item_unit_id');
    }
}
