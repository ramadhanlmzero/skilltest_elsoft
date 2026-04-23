<?php

namespace Tests\Feature;

use App\Models\CompanyModel;
use App\Models\ItemAccountGroupModel;
use App\Models\ItemGroupModel;
use App\Models\ItemTypeModel;
use App\Models\ItemUnitModel;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_item_list_endpoint_returns_success_response(): void
    {
        $headers = $this->authHeaders();

        $this->withHeaders($headers)
            ->getJson('/admin/api/item/list')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data', fn (array $value): bool => is_array($value));
    }

    public function test_item_create_endpoint_creates_new_item(): void
    {
        $headers = $this->authHeaders();
        [$companyId, $itemTypeId, $itemGroupId, $itemAccountGroupId, $itemUnitId] = $this->itemMasterIds();

        $this->withHeaders($headers)
            ->postJson('/admin/api/item', [
                'Company' => $companyId,
                'ItemType' => $itemTypeId,
                'Label' => 'Item API Test',
                'ItemGroup' => $itemGroupId,
                'ItemAccountGroup' => $itemAccountGroupId,
                'ItemUnit' => $itemUnitId,
                'IsActive' => true,
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.Label', 'Item API Test')
            ->assertJsonPath('data.Oid', fn (string $value): bool => $value !== '');
    }

    public function test_item_save_endpoint_updates_existing_item(): void
    {
        $headers = $this->authHeaders();
        [$companyId, $itemTypeId, $itemGroupId, $itemAccountGroupId, $itemUnitId] = $this->itemMasterIds();

        $created = $this->withHeaders($headers)->postJson('/admin/api/item', [
            'Company' => $companyId,
            'ItemType' => $itemTypeId,
            'Label' => 'Item Save Source',
            'ItemGroup' => $itemGroupId,
            'ItemAccountGroup' => $itemAccountGroupId,
            'ItemUnit' => $itemUnitId,
            'IsActive' => true,
        ]);

        $itemOid = (string) $created->json('data.Oid');

        $this->withHeaders($headers)
            ->postJson('/admin/api/item/save', [
                'Oid' => $itemOid,
                'Company' => $companyId,
                'ItemType' => $itemTypeId,
                'Label' => 'Item API Test Updated',
                'ItemGroup' => $itemGroupId,
                'ItemAccountGroup' => $itemAccountGroupId,
                'ItemUnit' => $itemUnitId,
                'IsActive' => false,
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.Oid', $itemOid)
            ->assertJsonPath('data.Label', 'Item API Test Updated')
            ->assertJsonPath('data.IsActive', false);
    }

    public function test_item_delete_endpoint_removes_existing_item(): void
    {
        $headers = $this->authHeaders();
        [$companyId, $itemTypeId, $itemGroupId, $itemAccountGroupId, $itemUnitId] = $this->itemMasterIds();

        $created = $this->withHeaders($headers)->postJson('/admin/api/item', [
            'Company' => $companyId,
            'ItemType' => $itemTypeId,
            'Label' => 'Item Delete Source',
            'ItemGroup' => $itemGroupId,
            'ItemAccountGroup' => $itemAccountGroupId,
            'ItemUnit' => $itemUnitId,
            'IsActive' => true,
        ]);

        $itemOid = (string) $created->json('data.Oid');

        $this->withHeaders($headers)
            ->deleteJson('/admin/api/item/delete', [
                'Oid' => $itemOid,
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data', null);
    }

    /**
     * @return array{string, string, string, string, string}
     */
    private function itemMasterIds(): array
    {
        $companyId = (string) CompanyModel::query()->where('domain', 'admin')->value('id');
        $itemTypeId = (string) ItemTypeModel::query()->value('id');
        $itemGroupId = (string) ItemGroupModel::query()->value('id');
        $itemAccountGroupId = (string) ItemAccountGroupModel::query()->value('id');
        $itemUnitId = (string) ItemUnitModel::query()->value('id');

        return [$companyId, $itemTypeId, $itemGroupId, $itemAccountGroupId, $itemUnitId];
    }

    /**
     * @return array<string, string>
     */
    private function authHeaders(): array
    {
        $signin = $this->postJson('/portal/api/auth/signin', [
            'domain' => 'admin',
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $token = (string) $signin->json('data.Token');

        return [
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ];
    }
}
