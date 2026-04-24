<?php

namespace App\Repositories\Item;

use App\Http\Requests\Item\CreateItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Models\ItemModel;
use Illuminate\Database\Eloquent\Collection;

class ItemRepository implements ItemRepositoryInterface
{
    /**
     * @return Collection<int, ItemModel>
     */
    public function getList(): Collection
    {
        return ItemModel::query()
            ->latest('created_at')
            ->get();
    }

    public function findById(string $id): ?ItemModel
    {
        return ItemModel::query()->find($id);
    }

    public function create(CreateItemRequest $request): ItemModel
    {
        $companyId = (string) $request->user()->company_id;

        return ItemModel::query()->create([
            'company_id' => $companyId,
            'item_type_id' => (string) $request->string('ItemType'),
            'code' => (string) $request->string('Code'),
            'label' => (string) $request->string('Label'),
            'item_group_id' => (string) $request->string('ItemGroup'),
            'item_account_group_id' => (string) $request->string('ItemAccountGroup'),
            'item_unit_id' => (string) $request->string('ItemUnit'),
            'is_active' => $request->boolean('IsActive'),
        ]);
    }

    public function update(ItemModel $item, UpdateItemRequest $request): ItemModel
    {
        $companyId = (string) $request->user()->company_id;

        $item->update([
            'company_id' => $companyId,
            'item_type_id' => (string) $request->string('ItemType'),
            'label' => (string) $request->string('Label'),
            'item_group_id' => (string) $request->string('ItemGroup'),
            'item_account_group_id' => (string) $request->string('ItemAccountGroup'),
            'item_unit_id' => (string) $request->string('ItemUnit'),
            'is_active' => $request->boolean('IsActive'),
        ]);

        return $item->refresh();
    }

    public function delete(ItemModel $item): void
    {
        $item->delete();
    }
}
