<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockIssueModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'stock_issues';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'company_id',
        'code',
        'date',
        'account_id',
        'status_id',
        'note',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyModel::class, 'company_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(AccountModel::class, 'account_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(StockIssueStatusModel::class, 'status_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(StockIssueDetailModel::class, 'stock_issue_id');
    }
}
