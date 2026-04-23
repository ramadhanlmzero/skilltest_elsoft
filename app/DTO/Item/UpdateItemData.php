<?php

namespace App\DTO\Item;

use App\Models\ItemModel;

class UpdateItemData
{
    public function __construct(
        public readonly string $oid,
        public readonly string $company,
        public readonly string $itemType,
        public readonly string $code,
        public readonly string $label,
        public readonly string $itemGroup,
        public readonly string $itemAccountGroup,
        public readonly string $itemUnit,
        public readonly bool $isActive,
    ) {
        //
    }

    public static function fromModel(ItemModel $item): self
    {
        return new self(
            oid: (string) $item->id,
            company: (string) $item->company_id,
            itemType: (string) $item->item_type_id,
            code: (string) $item->code,
            label: (string) $item->label,
            itemGroup: (string) $item->item_group_id,
            itemAccountGroup: (string) $item->item_account_group_id,
            itemUnit: (string) $item->item_unit_id,
            isActive: (bool) $item->is_active,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'Oid' => $this->oid,
            'Company' => $this->company,
            'ItemType' => $this->itemType,
            'Code' => $this->code,
            'Label' => $this->label,
            'ItemGroup' => $this->itemGroup,
            'ItemAccountGroup' => $this->itemAccountGroup,
            'ItemUnit' => $this->itemUnit,
            'IsActive' => $this->isActive,
        ];
    }
}
