<?php

namespace App\Http\Requests\StockIssue;

use App\Models\ItemModel;
use App\Models\StockIssueModel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStockIssueDetailRequest extends FormRequest
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
            'StockIssue' => ['required', 'uuid', Rule::exists(StockIssueModel::class, 'id')],
            'Item' => ['required', 'uuid', Rule::exists(ItemModel::class, 'id')],
            'Quantity' => ['required', 'numeric', 'gt:0'],
            'Note' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'StockIssue' => 'Stock Issue',
            'Item' => 'Item',
            'Quantity' => 'Quantity',
            'Note' => 'Note',
        ];
    }

    protected $stopOnFirstFailure = true;
}
