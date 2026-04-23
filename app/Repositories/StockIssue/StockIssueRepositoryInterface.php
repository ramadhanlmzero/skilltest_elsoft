<?php

namespace App\Repositories\StockIssue;

use App\Http\Requests\StockIssue\CreateStockIssueDetailRequest;
use App\Http\Requests\StockIssue\CreateStockIssueRequest;
use App\Http\Requests\StockIssue\UpdateStockIssueDetailRequest as UpdateDetailRequest;
use App\Http\Requests\StockIssue\UpdateStockIssueRequest;
use App\Models\StockIssueDetailModel;
use App\Models\StockIssueModel;
use Illuminate\Database\Eloquent\Collection;

interface StockIssueRepositoryInterface
{
    /**
     * @return Collection<int, StockIssueModel>
     */
    public function getList(): Collection;

    public function findById(string $id): ?StockIssueModel;

    public function create(CreateStockIssueRequest $request, array $status): StockIssueModel;

    public function update(StockIssueModel $stockIssue, UpdateStockIssueRequest $request): StockIssueModel;

    public function delete(StockIssueModel $stockIssue): void;

    public function findStatusByName(string $name): ?array;

    public function createDetail(string $stockIssueId, CreateStockIssueDetailRequest $request): StockIssueDetailModel;

    public function findDetailById(string $id): ?StockIssueDetailModel;

    public function updateDetail(StockIssueDetailModel $detail, UpdateDetailRequest $request): StockIssueDetailModel;

    public function deleteDetail(StockIssueDetailModel $detail): void;
}
