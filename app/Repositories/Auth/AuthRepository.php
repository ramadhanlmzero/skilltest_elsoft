<?php

namespace App\Repositories\Auth;

use App\Models\UserModel;

class AuthRepository implements AuthRepositoryInterface
{
    public function findByDomainAndUsername(string $domain, string $username): ?UserModel
    {
        return UserModel::query()
            ->where('username', $username)
            ->whereHas('company', function ($query) use ($domain) {
                $query->where('domain', $domain);
            })
            ->with(['company', 'role'])
            ->first();
    }
}
