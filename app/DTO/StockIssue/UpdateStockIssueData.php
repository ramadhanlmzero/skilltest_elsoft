<?php

namespace App\DTO\StockIssue;

use App\Models\StockIssueModel;

class UpdateStockIssueData
{
    public function __construct(
        public readonly string $company,
        public readonly string $code,
        public readonly string $date,
        public readonly string $account,
        public readonly ?string $note,
    ) {
        //
    }

    public static function fromModel(StockIssueModel $stockIssue): self
    {
        return new self(
            company: (string) $stockIssue->company_id,
            code: (string) $stockIssue->code,
            date: (string) $stockIssue->date?->format('Y-m-d'),
            account: (string) $stockIssue->account_id,
            note: $stockIssue->note,
        );
    }

    /**
     * @param  iterable<StockIssueModel>  $stockIssues
     * @return array<int, array<string, mixed>>
     */
    public static function collection(iterable $stockIssues): array
    {
        $result = [];

        foreach ($stockIssues as $stockIssue) {
            $result[] = self::fromModel($stockIssue)->toArray($stockIssue);
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(StockIssueModel $stockIssue): array
    {
        return [
            'Oid' => (string) $stockIssue->id,
            'Company' => $this->company,
            'CompanyName' => (string) $stockIssue->company?->domain,
            'Code' => $this->code,
            'Date' => $this->date,
            'Account' => $this->account,
            'AccountName' => (string) $stockIssue->account?->name,
            'Status' => (string) $stockIssue->status_id,
            'StatusName' => (string) $stockIssue->status?->name,
            'Note' => $this->note,
        ];
    }
}
