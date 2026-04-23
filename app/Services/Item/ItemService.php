<?php

namespace App\Services\Item;

use App\DTO\Item\CreateItemData;
use App\DTO\Item\GetItemData;
use App\DTO\Item\UpdateItemData;
use App\Helpers\CodeHelper;
use App\Http\Requests\Item\CreateItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Repositories\Item\ItemRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ItemService
{
    public function __construct(private readonly ItemRepositoryInterface $itemRepository)
    {
        //
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(): array
    {
        return GetItemData::collection($this->itemRepository->getList());
    }

    /**
     * @return array<string, mixed>
     */
    public function create(CreateItemRequest $request): array
    {
        $request->merge([
            'Code' => CodeHelper::item(),
        ]);

        $item = $this->itemRepository->create($request);

        return CreateItemData::fromModel($item)->toArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function save(string $id, UpdateItemRequest $request): array
    {
        $item = $this->itemRepository->findById($id);

        if (! $item) {
            throw new ModelNotFoundException('Item not found');
        }

        $updated = $this->itemRepository->update($item, $request);

        return UpdateItemData::fromModel($updated)->toArray();
    }

    public function delete(string $id): void
    {
        $item = $this->itemRepository->findById($id);

        if (! $item) {
            throw new ModelNotFoundException('Item not found');
        }

        $this->itemRepository->delete($item);
    }
}
