<?php

namespace App\DTO\StockIssue;

use App\Models\StockIssueModel;

class GetStockIssueData
{
    public function __construct(
        public readonly string $oid,
        public readonly string $company,
        public readonly string $code,
        public readonly string $date,
        public readonly string $account,
        public readonly string $status,
        public readonly ?string $note,
    ) {
        //
    }

    public static function fromModel(StockIssueModel $stockIssue): self
    {
        return new self(
            oid: (string) $stockIssue->id,
            company: (string) $stockIssue->company_id,
            code: (string) $stockIssue->code,
            date: (string) $stockIssue->date?->format('Y-m-d'),
            account: (string) $stockIssue->account_id,
            status: (string) $stockIssue->status_id,
            note: $stockIssue->note,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(StockIssueModel $stockIssue): array
    {
        return [
            'Oid' => $this->oid,
            'Company' => $this->company,
            'CompanyName' => (string) $stockIssue->company?->domain,
            'Code' => $this->code,
            'Date' => $this->date,
            'Account' => $this->account,
            'AccountName' => (string) $stockIssue->account?->name,
            'Status' => $this->status,
            'StatusName' => (string) $stockIssue->status?->name,
            'Note' => $this->note,
        ];
    }
}
