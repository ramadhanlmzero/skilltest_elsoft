<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockIssueDetailModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'stock_issue_details';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'stock_issue_id',
        'item_id',
        'quantity',
        'item_unit_id',
        'note',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
        ];
    }

    public function stockIssue(): BelongsTo
    {
        return $this->belongsTo(StockIssueModel::class, 'stock_issue_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemModel::class, 'item_id');
    }

    public function itemUnit(): BelongsTo
    {
        return $this->belongsTo(ItemUnitModel::class, 'item_unit_id');
    }
}
