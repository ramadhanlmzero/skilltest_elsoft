<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_signin_returns_bearer_token_payload(): void
    {
        $response = $this->postJson('/portal/api/auth/signin', [
            'domain' => 'admin',
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Success.')
            ->assertJsonPath('data.UserName', 'admin')
            ->assertJsonPath('data.Company', fn (string $value): bool => $value !== '')
            ->assertJsonPath('data.Token', fn (string $value): bool => str_contains($value, '|'));
    }

    public function test_logout_revokes_token(): void
    {
        $signin = $this->postJson('/portal/api/auth/signin', [
            'domain' => 'admin',
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $token = (string) $signin->json('data.Token');

        $logout = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ])->postJson('/portal/api/auth/logout');

        $logout
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Success.')
            ->assertJsonPath('data', null);

        $user = UserModel::query()->where('username', 'admin')->first();

        $this->assertNotNull($user);
        $this->assertSame(0, $user?->tokens()->count());
    }
}
