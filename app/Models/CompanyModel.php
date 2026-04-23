<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'companies';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'domain',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(UserModel::class, 'company_id');
    }
}
