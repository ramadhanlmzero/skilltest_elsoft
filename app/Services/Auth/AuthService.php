<?php

namespace App\Services\Auth;

use App\DTO\Auth\SigninData;
use App\Models\UserModel;
use App\Repositories\Auth\AuthRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(private readonly AuthRepositoryInterface $authRepository)
    {
        //
    }

    /**
     * @return array<string, mixed>
     */
    public function signin(SigninData $data): array
    {
        $user = $this->authRepository->findByDomainAndUsername($data->domain, $data->username);

        if (! $user || ! Hash::check($data->password, $user->password)) {
            throw new AuthenticationException('Invalid credentials');
        }

        $tokenExpirationHours = env('TOKEN_EXPIRATION_HOURS', 3);

        $device = 'web_'.now()->valueOf();
        $token = $user->createToken($device, ['*'], now()->addHours($tokenExpirationHours));

        return [
            'UserName' => $user->username,
            'Password' => $data->password,
            'Company' => $user->company_id,
            'browserInfo' => $this->browserInfo($data->userAgent),
            'machineInfo' => $this->machineInfo($data->userAgent),
            'osInfo' => $this->osInfo($data->userAgent),
            'osNameInfo' => $this->osNameInfo($data->userAgent),
            'Device' => $device,
            'Model' => $this->deviceModel($user),
            'Source' => $data->ipAddress,
            'Exp' => $tokenExpirationHours,
            'Token' => $token->plainTextToken,
        ];
    }

    /**
     * @return array<string, bool>
     */
    private function browserInfo(string $userAgent): array
    {
        $isEdge = str_contains($userAgent, 'Edg/');
        $isChrome = str_contains($userAgent, 'Chrome/') && ! $isEdge;
        $isSafari = str_contains($userAgent, 'Safari/') && ! $isChrome && ! $isEdge;
        $isMobile = $this->isMobile($userAgent);

        return [
            'chrome' => $isChrome,
            'chrome_view' => false,
            'chrome_mobile' => $isChrome && $isMobile && ! str_contains($userAgent, 'iPhone'),
            'chrome_mobile_ios' => $isChrome && str_contains($userAgent, 'iPhone'),
            'safari' => $isSafari && ! $isMobile,
            'safari_mobile' => $isSafari && $isMobile,
            'msedge' => $isEdge,
            'msie_mobile' => false,
            'msie' => str_contains($userAgent, 'MSIE') || str_contains($userAgent, 'Trident/'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function machineInfo(string $userAgent): array
    {
        $osName = $this->detectOsName($userAgent);

        return [
            'brand' => $this->detectBrand($userAgent, $osName),
            'model' => $this->detectModel($userAgent),
            'os_name' => $osName,
            'os_version' => $this->detectOsVersion($userAgent, $osName),
            'type' => $this->detectDeviceType($userAgent),
        ];
    }

    /**
     * @return array<string, bool>
     */
    private function osInfo(string $userAgent): array
    {
        $osName = $this->detectOsName($userAgent);

        return [
            'android' => $osName === 'Android',
            'blackberry' => str_contains($userAgent, 'BlackBerry'),
            'ios' => in_array($osName, ['iOS', 'iPadOS'], true),
            'windows' => $osName === 'Windows',
            'windows_phone' => str_contains($userAgent, 'Windows Phone'),
            'mac' => $osName === 'Mac',
            'linux' => $osName === 'Linux',
            'chrome' => str_contains($userAgent, 'CrOS'),
            'firefox' => str_contains($userAgent, 'Firefox'),
            'gamingConsole' => preg_match('/PlayStation|Xbox|Nintendo/i', $userAgent) === 1,
        ];
    }

    /**
     * @return array{name: string, version: string, platform: string}
     */
    private function osNameInfo(string $userAgent): array
    {
        $osName = $this->detectOsName($userAgent);

        return [
            'name' => $osName,
            'version' => $this->detectOsVersion($userAgent, $osName),
            'platform' => $this->platform($userAgent),
        ];
    }

    private function detectOsName(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'Windows') => 'Windows',
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'iPhone'), str_contains($userAgent, 'iPad') => 'iOS',
            str_contains($userAgent, 'Mac OS X'), str_contains($userAgent, 'Macintosh') => 'Mac',
            str_contains($userAgent, 'Linux') => 'Linux',
            default => 'Unknown',
        };
    }

    private function detectOsVersion(string $userAgent, string $osName): string
    {
        return match ($osName) {
            'Windows' => $this->matchVersion($userAgent, '/Windows NT ([\d.]+)/'),
            'Android' => $this->matchVersion($userAgent, '/Android ([\d.]+)/'),
            'iOS' => str_replace('_', '.', $this->matchVersion($userAgent, '/OS ([\d_]+)/')),
            'Mac' => str_replace('_', '.', $this->matchVersion($userAgent, '/Mac OS X ([\d_]+)/')),
            'Linux' => $this->matchVersion($userAgent, '/Linux ([\w.]+)/'),
            default => '',
        };
    }

    private function detectBrand(string $userAgent, string $osName): string
    {
        return match (true) {
            in_array($osName, ['Mac', 'iOS'], true) => 'Apple',
            str_contains($userAgent, 'Samsung') => 'Samsung',
            str_contains($userAgent, 'Windows') => 'Microsoft',
            str_contains($userAgent, 'Linux') => 'Generic',
            default => '',
        };
    }

    private function detectModel(string $userAgent): string
    {
        if (preg_match('/\(([^)]+)\)/', $userAgent, $matches) !== 1) {
            return '';
        }

        $parts = array_map('trim', explode(';', $matches[1]));
        $candidates = array_values(array_filter($parts, static function (string $part): bool {
            return ! preg_match('/Mozilla|Windows|Macintosh|Intel|Android|CPU|Linux|X11|rv:/i', $part);
        }));

        return $candidates[0] ?? '';
    }

    private function detectDeviceType(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'iPad'), str_contains($userAgent, 'Tablet') => 'tablet',
            $this->isMobile($userAgent) => 'mobile',
            default => 'desktop',
        };
    }

    private function platform(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'ARM') => 'ARM',
            str_contains($userAgent, 'Win64'),
            str_contains($userAgent, 'x64'),
            str_contains($userAgent, 'x86_64') => 'x64',
            str_contains($userAgent, 'i686'), str_contains($userAgent, 'x86') => 'x86',
            default => '',
        };
    }

    private function isMobile(string $userAgent): bool
    {
        return preg_match('/Mobile|iPhone|Android/i', $userAgent) === 1;
    }

    private function matchVersion(string $userAgent, string $pattern): string
    {
        if (preg_match($pattern, $userAgent, $matches) !== 1) {
            return '';
        }

        return $matches[1] ?? '';
    }

    private function deviceModel(UserModel $user): string
    {
        $role = Str::lower((string) $user->role?->name);

        return in_array($role, ['admin', 'superadmin'], true) ? 'Admin Web' : 'User Web';
    }
}
