<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Item\CreateItemRequest;
use App\Http\Requests\Item\DeleteItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Services\Item\ItemService;

class ItemController extends Controller
{
    public function __construct(private readonly ItemService $itemService)
    {
        //
    }

    public function list()
    {
        return ResponseHelper::success($this->itemService->list());
    }

    public function create(CreateItemRequest $request)
    {
        $result = $this->itemService->create($request);

        return ResponseHelper::success($result);
    }

    public function save(UpdateItemRequest $request)
    {
        $result = $this->itemService->save((string) $request->string('Oid'), $request);

        return ResponseHelper::success($result);
    }

    public function delete(DeleteItemRequest $request)
    {
        $this->itemService->delete((string) $request->string('Oid'));

        return ResponseHelper::success();
    }
}
