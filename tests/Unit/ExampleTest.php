<?php

namespace Tests\Unit;

use App\Models\CompanyModel;
use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    private const TEST_COMPANY = 'd3170153-6b16-4397-bf89-96533ee149ee';

    private const ADMIN_ROLE = '7a4a41e5-016c-48aa-b9ff-4ed3ba4f30d4';

    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_signin_returns_expected_payload_for_valid_credentials(): void
    {
        CompanyModel::query()->create([
            'id' => self::TEST_COMPANY,
            'name' => 'Testcase Company',
            'domain' => 'testcase',
        ]);

        RoleModel::query()->create([
            'id' => self::ADMIN_ROLE,
            'name' => 'admin',
        ]);

        UserModel::query()->create([
            'name' => 'testcase',
            'username' => 'testcase',
            'email' => 'testcase@example.com',
            'password' => Hash::make('testcase123'),
            'company_id' => self::TEST_COMPANY,
            'role_id' => self::ADMIN_ROLE,
        ]);

        $userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) '
            .'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

        $response = $this
            ->withHeader('User-Agent', $userAgent)
            ->withServerVariables(['REMOTE_ADDR' => '103.242.150.163'])
            ->postJson('/api/auth/signin', [
                'domain' => 'testcase',
                'username' => 'testcase',
                'password' => 'testcase123',
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'UserName' => 'testcase',
                'Password' => 'testcase123',
                'Company' => self::TEST_COMPANY,
                'browserInfo' => [
                    'chrome' => true,
                    'chrome_view' => false,
                    'chrome_mobile' => false,
                    'chrome_mobile_ios' => false,
                    'safari' => false,
                    'safari_mobile' => false,
                    'msedge' => false,
                    'msie_mobile' => false,
                    'msie' => false,
                ],
                'machineInfo' => [
                    'brand' => 'Apple',
                    'os_name' => 'Mac',
                    'os_version' => '10.15.7',
                    'type' => 'desktop',
                ],
                'osInfo' => [
                    'android' => false,
                    'blackberry' => false,
                    'ios' => false,
                    'windows' => false,
                    'windows_phone' => false,
                    'mac' => true,
                    'linux' => false,
                    'chrome' => false,
                    'firefox' => false,
                    'gamingConsole' => false,
                ],
                'osNameInfo' => [
                    'name' => 'Mac',
                    'version' => '10.15.7',
                    'platform' => '',
                ],
                'Model' => 'Admin Web',
                'Source' => '103.242.150.163',
                'Exp' => 3,
                'token_type' => 'Bearer',
            ])
            ->assertJsonPath('machineInfo.model', '')
            ->assertJsonPath('Device', fn (string $device): bool => str_starts_with($device, 'web_'))
            ->assertJsonPath('access_token', fn (string $token): bool => str_contains($token, '|'));
    }

    public function test_signin_returns_unauthorized_for_invalid_credentials(): void
    {
        CompanyModel::query()->create([
            'id' => self::TEST_COMPANY,
            'name' => 'Testcase Company',
            'domain' => 'testcase',
        ]);

        RoleModel::query()->create([
            'id' => self::ADMIN_ROLE,
            'name' => 'admin',
        ]);

        UserModel::query()->create([
            'name' => 'testcase',
            'username' => 'testcase',
            'email' => 'testcase@example.com',
            'password' => Hash::make('different-password'),
            'company_id' => self::TEST_COMPANY,
            'role_id' => self::ADMIN_ROLE,
        ]);

        $response = $this->postJson('/api/auth/signin', [
            'domain' => 'testcase',
            'username' => 'testcase',
            'password' => 'testcase123',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials',
                'data' => null,
            ]);
    }
}
