<?php

namespace App\DTO\StockIssue;

use App\Models\StockIssueModel;

class CreateStockIssueData
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
     * @return array<string, mixed>
     */
    public function toArray(StockIssueModel $stockIssue): array
    {
        return [
            'Company' => $this->company,
            'CompanyName' => (string) $stockIssue->company?->domain,
            'Code' => $this->code,
            'Date' => $this->date,
            'Account' => $this->account,
            'AccountName' => (string) $stockIssue->account?->name,
            'Note' => $this->note,
        ];
    }
}
