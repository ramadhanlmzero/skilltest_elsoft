<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Sanctum\PersonalAccessToken;

class PersonalAccessTokenModel extends PersonalAccessToken
{
    use HasUuids;

    protected $table = 'personal_access_tokens';

    public $incrementing = false;

    protected $keyType = 'string';
}
