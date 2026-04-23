<?php

namespace App\DTO\Auth;

use App\Http\Requests\Auth\SigninRequest;

class SigninData
{
    public function __construct(
        public readonly string $domain,
        public readonly string $username,
        public readonly string $password,
        public readonly string $userAgent,
        public readonly string $ipAddress,
    ) {
        //
    }

    public static function fromRequest(SigninRequest $request): self
    {
        return new self(
            domain: (string) $request->string('domain'),
            username: (string) $request->string('username'),
            password: (string) $request->string('password'),
            userAgent: (string) $request->userAgent(),
            ipAddress: (string) $request->ip(),
        );
    }
}
