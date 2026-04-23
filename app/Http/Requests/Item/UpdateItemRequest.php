<?php

namespace App\Http\Requests\Item;

use App\Models\ItemModel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends CreateItemRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Oid' => ['required', 'uuid', Rule::exists(ItemModel::class, 'id')],
            ...parent::rules(),
        ];
    }

    public function attributes(): array
    {
        return [
            ...parent::attributes(),
            'Oid' => 'Oid',
        ];
    }

    protected $stopOnFirstFailure = true;
}
