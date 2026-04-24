<?php

namespace App\Repositories\StockIssue;

use App\Helpers\CodeHelper;
use App\Http\Requests\StockIssue\CreateStockIssueDetailRequest;
use App\Http\Requests\StockIssue\CreateStockIssueRequest;
use App\Http\Requests\StockIssue\UpdateStockIssueDetailRequest as UpdateDetailRequest;
use App\Http\Requests\StockIssue\UpdateStockIssueRequest;
use App\Models\AccountModel;
use App\Models\ItemModel;
use App\Models\StockIssueDetailModel;
use App\Models\StockIssueModel;
use App\Models\StockIssueStatusModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StockIssueRepository implements StockIssueRepositoryInterface
{
    /**
     * @var list<string>
     */
    private array $stockIssueRelations = ['company', 'account', 'status'];

    /**
     * @var list<string>
     */
    private array $detailRelations = ['item', 'itemUnit'];

    /**
     * @return Collection<int, StockIssueModel>
     */
    public function getList(): Collection
    {
        return StockIssueModel::query()
            ->with($this->stockIssueRelations)
            ->latest('created_at')
            ->get();
    }

    public function findById(string $id): ?StockIssueModel
    {
        return StockIssueModel::query()
            ->with($this->stockIssueRelations)
            ->find($id);
    }

    public function create(CreateStockIssueRequest $request, array $status): StockIssueModel
    {
        $companyId = (string) $request->user()->company_id;
        $accountId = (string) $request->string('Account');
        $account = AccountModel::query()
            ->whereKey($accountId)
            ->where('company_id', $companyId)
            ->first();

        if (! $account) {
            throw new ModelNotFoundException('Account not found for current user company');
        }

        $stockIssue = StockIssueModel::query()->create([
            'company_id' => $companyId,
            'code' => CodeHelper::stockIssue(),
            'date' => (string) $request->string('Date'),
            'account_id' => $accountId,
            'status_id' => (string) $status['status_id'],
            'note' => $request->input('Note') !== null ? (string) $request->input('Note') : null,
        ]);

        return $stockIssue->load($this->stockIssueRelations);
    }

    public function update(StockIssueModel $stockIssue, UpdateStockIssueRequest $request): StockIssueModel
    {
        $companyId = (string) $request->user()->company_id;
        $accountId = (string) $request->string('Account');
        $account = AccountModel::query()
            ->whereKey($accountId)
            ->where('company_id', $companyId)
            ->first();

        if (! $account) {
            throw new ModelNotFoundException('Account not found for current user company');
        }

        $stockIssue->update([
            'company_id' => $companyId,
            'date' => (string) $request->string('Date'),
            'account_id' => $accountId,
            'status_id' => (string) $stockIssue->status_id,
            'note' => $request->input('Note') !== null ? (string) $request->input('Note') : null,
        ]);

        return $stockIssue->refresh()->load($this->stockIssueRelations);
    }

    public function delete(StockIssueModel $stockIssue): void
    {
        $stockIssue->details()->delete();
        $stockIssue->delete();
    }

    public function findStatusByName(string $name): ?array
    {
        $status = StockIssueStatusModel::query()->where('name', $name)->first();

        if (! $status) {
            return null;
        }

        return [
            'status_id' => (string) $status->id,
        ];
    }

    public function createDetail(string $stockIssueId, CreateStockIssueDetailRequest $request): StockIssueDetailModel
    {
        $itemId = (string) $request->string('Item');
        $item = ItemModel::query()
            ->with('itemUnit')
            ->find($itemId);

        if (! $item || ! $item->item_unit_id) {
            throw new ModelNotFoundException('Item or item unit not found');
        }

        $detail = StockIssueDetailModel::query()->create([
            'stock_issue_id' => $stockIssueId,
            'item_id' => $itemId,
            'quantity' => (string) $request->string('Quantity'),
            'item_unit_id' => (string) $item->item_unit_id,
            'note' => $request->input('Note') !== null ? (string) $request->input('Note') : null,
        ]);

        return $detail->load($this->detailRelations);
    }

    public function findDetailById(string $id): ?StockIssueDetailModel
    {
        return StockIssueDetailModel::query()
            ->with($this->detailRelations)
            ->find($id);
    }

    public function updateDetail(StockIssueDetailModel $detail, UpdateDetailRequest $request): StockIssueDetailModel
    {
        $itemId = (string) $request->string('Item');
        $item = ItemModel::query()
            ->with('itemUnit')
            ->find($itemId);

        if (! $item || ! $item->item_unit_id) {
            throw new ModelNotFoundException('Item or item unit not found');
        }

        $detail->update([
            'item_id' => $itemId,
            'quantity' => (string) $request->string('Quantity'),
            'item_unit_id' => (string) $item->item_unit_id,
            'note' => $request->input('Note') !== null ? (string) $request->input('Note') : null,
        ]);

        return $detail->refresh()->load($this->detailRelations);
    }

    public function deleteDetail(StockIssueDetailModel $detail): void
    {
        $detail->delete();
    }
}
