<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockIssueStatusModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'stock_issue_statuses';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
    ];

    public function stockIssues(): HasMany
    {
        return $this->hasMany(StockIssueModel::class, 'status_id');
    }
}
