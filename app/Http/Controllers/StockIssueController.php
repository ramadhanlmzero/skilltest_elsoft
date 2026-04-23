<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StockIssue\CreateStockIssueDetailRequest;
use App\Http\Requests\StockIssue\CreateStockIssueRequest;
use App\Http\Requests\StockIssue\DeleteStockIssueDetailRequest;
use App\Http\Requests\StockIssue\DeleteStockIssueRequest;
use App\Http\Requests\StockIssue\UpdateStockIssueDetailRequest;
use App\Http\Requests\StockIssue\UpdateStockIssueRequest;
use App\Services\StockIssue\StockIssueService;

class StockIssueController extends Controller
{
    public function __construct(private readonly StockIssueService $stockIssueService)
    {
        //
    }

    public function list()
    {
        return ResponseHelper::success($this->stockIssueService->list());
    }

    public function create(CreateStockIssueRequest $request)
    {
        $result = $this->stockIssueService->create($request);

        return ResponseHelper::success($result);
    }

    public function get(string $oid)
    {
        return ResponseHelper::success($this->stockIssueService->get($oid));
    }

    public function save(UpdateStockIssueRequest $request, string $oid)
    {
        $result = $this->stockIssueService->save($oid, $request);

        return ResponseHelper::success($result);
    }

    public function delete(DeleteStockIssueRequest $request, string $oid)
    {
        $request->validated();
        $this->stockIssueService->delete($oid);

        return ResponseHelper::success();
    }

    public function createDetail(CreateStockIssueDetailRequest $request)
    {
        $stockIssueId = (string) $request->string('StockIssue');
        $result = $this->stockIssueService->createDetail($stockIssueId, $request);

        return ResponseHelper::success($result);
    }

    public function getDetail(string $oid)
    {
        return ResponseHelper::success($this->stockIssueService->getDetail($oid));
    }

    public function saveDetail(UpdateStockIssueDetailRequest $request, string $oid)
    {
        $result = $this->stockIssueService->saveDetail($oid, $request);

        return ResponseHelper::success($result);
    }

    public function deleteDetail(DeleteStockIssueDetailRequest $request, string $oid)
    {
        $request->validated();
        $this->stockIssueService->deleteDetail($oid);

        return ResponseHelper::success();
    }
}
