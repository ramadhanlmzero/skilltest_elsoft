<?php

namespace App\Repositories\Item;

use App\Http\Requests\Item\CreateItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Models\ItemModel;
use Illuminate\Database\Eloquent\Collection;

interface ItemRepositoryInterface
{
    /**
     * @return Collection<int, ItemModel>
     */
    public function getList(): Collection;

    public function findById(string $id): ?ItemModel;

    public function create(CreateItemRequest $request): ItemModel;

    public function update(ItemModel $item, UpdateItemRequest $request): ItemModel;

    public function delete(ItemModel $item): void;
}
