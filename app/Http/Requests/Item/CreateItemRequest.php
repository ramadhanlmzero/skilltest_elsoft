<?php

namespace App\Http\Requests\Item;

use App\Models\CompanyModel;
use App\Models\ItemAccountGroupModel;
use App\Models\ItemGroupModel;
use App\Models\ItemTypeModel;
use App\Models\ItemUnitModel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Company' => ['required', 'uuid', Rule::exists(CompanyModel::class, 'id')],
            'ItemType' => ['required', 'uuid', Rule::exists(ItemTypeModel::class, 'id')],
            'Label' => ['required', 'string', 'max:255'],
            'ItemGroup' => ['required', 'uuid', Rule::exists(ItemGroupModel::class, 'id')],
            'ItemAccountGroup' => ['required', 'uuid', Rule::exists(ItemAccountGroupModel::class, 'id')],
            'ItemUnit' => ['required', 'uuid', Rule::exists(ItemUnitModel::class, 'id')],
            'IsActive' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'Company' => 'Company',
            'ItemType' => 'Item Type',
            'Code' => 'Code',
            'Label' => 'Title',
            'ItemGroup' => 'Item Group',
            'ItemAccountGroup' => 'Item Account Group',
            'ItemUnit' => 'Item Unit',
            'IsActive' => 'Is Active',
        ];
    }

    protected $stopOnFirstFailure = true;
}
