<?php

namespace App\Http\Requests\Item;

use App\Models\ItemModel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteItemRequest extends FormRequest
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
            'Oid' => ['required', 'uuid', Rule::exists(ItemModel::class, 'id')],
        ];
    }

    public function attributes(): array
    {
        return [
            'Oid' => 'Oid',
        ];
    }

    protected $stopOnFirstFailure = true;
}
