<?php

namespace App\Repositories\Auth;

use App\Models\UserModel;

interface AuthRepositoryInterface
{
    public function findByDomainAndUsername(string $domain, string $username): ?UserModel;
}
