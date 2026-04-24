<?php

namespace Tests\Feature;

use App\Models\AccountModel;
use App\Models\ItemAccountGroupModel;
use App\Models\ItemGroupModel;
use App\Models\ItemModel;
use App\Models\ItemTypeModel;
use App\Models\ItemUnitModel;
use App\Models\StockIssueDetailModel;
use App\Models\StockIssueModel;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StockIssueApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_stock_issue_list_endpoint_returns_success_response(): void
    {
        [$headers, $companyId] = $this->authContext();

        $this->assertNotSame('', $companyId);

        $this->withHeaders($headers)
            ->getJson('/admin/api/v1/stockissue/list')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data', fn (array $value): bool => is_array($value));
    }

    public function test_stock_issue_create_endpoint_creates_parent(): void
    {
        [$headers, $companyId] = $this->authContext();
        $accountId = (string) AccountModel::query()->where('company_id', $companyId)->value('id');

        $this->withHeaders($headers)
            ->postJson('/admin/api/v1/stockissue', [
                'Date' => '2023-12-28',
                'Account' => $accountId,
                'Note' => 'create from test',
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.Company', $companyId)
            ->assertJsonPath('data.Account', $accountId);
    }

    public function test_stock_issue_get_endpoint_returns_parent_detail(): void
    {
        [$headers, $companyId] = $this->authContext();
        $stockIssueOid = $this->createStockIssue($headers, $companyId);

        $this->withHeaders($headers)
            ->getJson('/admin/api/v1/stockissue/'.$stockIssueOid)
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.Oid', $stockIssueOid);
    }

    public function test_stock_issue_save_endpoint_updates_parent(): void
    {
        [$headers, $companyId] = $this->authContext();
        $stockIssueOid = $this->createStockIssue($headers, $companyId);
        $accountId = (string) AccountModel::query()->where('company_id', $companyId)->value('id');

        $this->withHeaders($headers)
            ->postJson('/admin/api/v1/stockissue/'.$stockIssueOid, [
                'Date' => '2023-12-29',
                'Account' => $accountId,
                'Note' => 'updated from test',
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.Oid', $stockIssueOid)
            ->assertJsonPath('data.Note', 'updated from test');
    }

    public function test_stock_issue_delete_endpoint_removes_parent(): void
    {
        [$headers, $companyId] = $this->authContext();
        $stockIssueOid = $this->createStockIssue($headers, $companyId);

        $this->withHeaders($headers)
            ->deleteJson('/admin/api/v1/stockissue/'.$stockIssueOid)
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data', null);
    }

    public function test_stock_issue_detail_create_endpoint_creates_detail(): void
    {
        [$headers, $companyId] = $this->authContext();
        $stockIssueOid = $this->createStockIssue($headers, $companyId);
        $item = $this->createItemForDetail($companyId);

        $this->withHeaders($headers)
            ->postJson('/admin/api/v1/stockissue/detail', [
                'StockIssue' => $stockIssueOid,
                'Item' => (string) $item->id,
                'Quantity' => 3,
                'Note' => 'detail note',
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.Item', (string) $item->id);
    }

    public function test_stock_issue_detail_get_endpoint_returns_detail_data(): void
    {
        [$headers, $companyId] = $this->authContext();
        $stockIssueOid = $this->createStockIssue($headers, $companyId);
        $item = $this->createItemForDetail($companyId);
        $detailOid = $this->createStockIssueDetail($headers, $stockIssueOid, (string) $item->id);

        $this->withHeaders($headers)
            ->getJson('/admin/api/v1/stockissue/detail/'.$detailOid)
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.Oid', $detailOid);
    }

    public function test_stock_issue_detail_save_endpoint_updates_detail(): void
    {
        [$headers, $companyId] = $this->authContext();
        $stockIssueOid = $this->createStockIssue($headers, $companyId);
        $item = $this->createItemForDetail($companyId);
        $detailOid = $this->createStockIssueDetail($headers, $stockIssueOid, (string) $item->id);

        $this->withHeaders($headers)
            ->postJson('/admin/api/v1/stockissue/detail/'.$detailOid, [
                'Oid' => $detailOid,
                'Item' => (string) $item->id,
                'Quantity' => 5,
                'Note' => 'detail updated',
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.Oid', $detailOid)
            ->assertJsonPath('data.Quantity', '5')
            ->assertJsonPath('data.Note', 'detail updated');
    }

    public function test_stock_issue_detail_delete_endpoint_removes_detail(): void
    {
        [$headers, $companyId] = $this->authContext();
        $stockIssueOid = $this->createStockIssue($headers, $companyId);
        $item = $this->createItemForDetail($companyId);
        $detailOid = $this->createStockIssueDetail($headers, $stockIssueOid, (string) $item->id);

        $this->withHeaders($headers)
            ->deleteJson('/admin/api/v1/stockissue/detail/'.$detailOid, [
                'Oid' => $detailOid,
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data', null);
    }

    /**
     * @return array{0: array<string, string>, 1: string}
     */
    private function authContext(): array
    {
        $signin = $this->postJson('/portal/api/auth/signin', [
            'domain' => 'testcase',
            'username' => 'testcase',
            'password' => 'testcase123',
        ]);

        $token = (string) $signin->json('data.Token');
        $companyId = (string) $signin->json('data.Company');

        return [
            [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
            ],
            $companyId,
        ];
    }

    /**
     * @param  array<string, string>  $headers
     */
    private function createStockIssue(array $headers, string $companyId): string
    {
        $accountId = (string) AccountModel::query()->where('company_id', $companyId)->value('id');

        $response = $this->withHeaders($headers)->postJson('/admin/api/v1/stockissue', [
            'Date' => '2023-12-28',
            'Account' => $accountId,
            'Note' => 'create from test',
        ]);

        $response->assertOk();

        $stockIssueCode = (string) $response->json('data.Code');
        $stockIssue = StockIssueModel::query()->where('code', $stockIssueCode)->first();

        $this->assertNotNull($stockIssue);

        return (string) $stockIssue?->id;
    }

    /**
     * @param  array<string, string>  $headers
     */
    private function createStockIssueDetail(array $headers, string $stockIssueOid, string $itemId): string
    {
        $response = $this->withHeaders($headers)->postJson('/admin/api/v1/stockissue/detail', [
            'StockIssue' => $stockIssueOid,
            'Item' => $itemId,
            'Quantity' => 3,
            'Note' => 'detail note',
        ]);

        $response->assertOk();

        $detail = StockIssueDetailModel::query()
            ->where('stock_issue_id', $stockIssueOid)
            ->where('item_id', $itemId)
            ->first();

        $this->assertNotNull($detail);

        return (string) $detail?->id;
    }

    private function createItemForDetail(string $companyId): ItemModel
    {
        return ItemModel::query()->create([
            'company_id' => $companyId,
            'item_type_id' => (string) ItemTypeModel::query()->value('id'),
            'code' => 'ITM-'.Str::upper(Str::random(6)),
            'label' => 'Item For Detail Test',
            'item_group_id' => (string) ItemGroupModel::query()->value('id'),
            'item_account_group_id' => (string) ItemAccountGroupModel::query()->value('id'),
            'item_unit_id' => (string) ItemUnitModel::query()->value('id'),
            'is_active' => true,
        ]);
    }
}
