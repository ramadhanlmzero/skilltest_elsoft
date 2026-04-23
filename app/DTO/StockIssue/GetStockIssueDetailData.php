<?php

namespace App\DTO\StockIssue;

use App\Models\StockIssueDetailModel;

class GetStockIssueDetailData
{
    public function __construct(
        public readonly string $oid,
        public readonly string $item,
        public readonly string $itemName,
        public readonly string $quantity,
        public readonly string $itemUnit,
        public readonly string $itemUnitName,
        public readonly ?string $note,
    ) {
        //
    }

    public static function fromModel(StockIssueDetailModel $detail): self
    {
        return new self(
            oid: (string) $detail->id,
            item: (string) $detail->item_id,
            itemName: (string) $detail->item?->label,
            quantity: self::normalizeQuantity((string) $detail->quantity),
            itemUnit: (string) $detail->item_unit_id,
            itemUnitName: (string) $detail->itemUnit?->name,
            note: $detail->note,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'Oid' => $this->oid,
            'Item' => $this->item,
            'ItemName' => $this->itemName,
            'Quantity' => $this->quantity,
            'ItemUnit' => $this->itemUnit,
            'ItemUnitName' => $this->itemUnitName,
            'Note' => $this->note,
        ];
    }

    private static function normalizeQuantity(string $quantity): string
    {
        return rtrim(rtrim($quantity, '0'), '.');
    }
}
