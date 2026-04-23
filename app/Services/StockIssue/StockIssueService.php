<?php

namespace App\Services\StockIssue;

use App\DTO\StockIssue\CreateStockIssueData;
use App\DTO\StockIssue\CreateStockIssueDetailData;
use App\DTO\StockIssue\GetStockIssueData;
use App\DTO\StockIssue\GetStockIssueDetailData;
use App\DTO\StockIssue\UpdateStockIssueData;
use App\DTO\StockIssue\UpdateStockIssueDetailData;
use App\Helpers\CodeHelper;
use App\Http\Requests\StockIssue\CreateStockIssueDetailRequest;
use App\Http\Requests\StockIssue\CreateStockIssueRequest;
use App\Http\Requests\StockIssue\UpdateStockIssueDetailRequest;
use App\Http\Requests\StockIssue\UpdateStockIssueRequest;
use App\Repositories\StockIssue\StockIssueRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StockIssueService
{
    public function __construct(private readonly StockIssueRepositoryInterface $stockIssueRepository)
    {
        //
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(): array
    {
        return UpdateStockIssueData::collection($this->stockIssueRepository->getList());
    }

    /**
     * @return array<string, mixed>
     */
    public function create(CreateStockIssueRequest $request): array
    {
        $status = $this->stockIssueRepository->findStatusByName('Entry');

        if (! $status) {
            throw new ModelNotFoundException('Stock issue status Entry not found');
        }

        $request->merge([
            'Code' => CodeHelper::stockIssue(),
        ]);

        $stockIssue = $this->stockIssueRepository->create($request, $status);

        return CreateStockIssueData::fromModel($stockIssue)->toArray($stockIssue);
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        $stockIssue = $this->stockIssueRepository->findById($id);

        if (! $stockIssue) {
            throw new ModelNotFoundException('Stock issue not found');
        }

        return GetStockIssueData::fromModel($stockIssue)->toArray($stockIssue);
    }

    /**
     * @return array<string, mixed>
     */
    public function save(string $id, UpdateStockIssueRequest $request): array
    {
        $stockIssue = $this->stockIssueRepository->findById($id);

        if (! $stockIssue) {
            throw new ModelNotFoundException('Stock issue not found');
        }

        $updated = $this->stockIssueRepository->update($stockIssue, $request);

        return UpdateStockIssueData::fromModel($updated)->toArray($updated);
    }

    public function delete(string $id): void
    {
        $stockIssue = $this->stockIssueRepository->findById($id);

        if (! $stockIssue) {
            throw new ModelNotFoundException('Stock issue not found');
        }

        $this->stockIssueRepository->delete($stockIssue);
    }

    /**
     * @return array<string, mixed>
     */
    public function getDetail(string $detailId): array
    {
        $detail = $this->stockIssueRepository->findDetailById($detailId);

        if (! $detail) {
            throw new ModelNotFoundException('Stock issue detail not found');
        }

        return GetStockIssueDetailData::fromModel($detail)->toArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function createDetail(string $stockIssueId, CreateStockIssueDetailRequest $request): array
    {
        $stockIssue = $this->stockIssueRepository->findById($stockIssueId);

        if (! $stockIssue) {
            throw new ModelNotFoundException('Stock issue not found');
        }

        $detail = $this->stockIssueRepository->createDetail($stockIssueId, $request);

        return CreateStockIssueDetailData::fromModel($detail)->toArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function saveDetail(string $detailId, UpdateStockIssueDetailRequest $request): array
    {
        $detail = $this->stockIssueRepository->findDetailById($detailId);

        if (! $detail) {
            throw new ModelNotFoundException('Stock issue detail not found');
        }

        $updated = $this->stockIssueRepository->updateDetail($detail, $request);

        return UpdateStockIssueDetailData::fromModel($updated)->toArray($updated);
    }

    public function deleteDetail(string $detailId): void
    {
        $detail = $this->stockIssueRepository->findDetailById($detailId);

        if (! $detail) {
            throw new ModelNotFoundException('Stock issue detail not found');
        }

        $this->stockIssueRepository->deleteDetail($detail);
    }
}
