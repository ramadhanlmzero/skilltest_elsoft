<?php

namespace App\Http\Requests\StockIssue;

use App\Models\StockIssueDetailModel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateStockIssueDetailRequest extends CreateStockIssueDetailRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Oid' => ['required', 'uuid', Rule::exists(StockIssueDetailModel::class, 'id')],
            'Item' => parent::rules()['Item'],
            'Quantity' => parent::rules()['Quantity'],
            'Note' => parent::rules()['Note'],
        ];
    }

    protected $stopOnFirstFailure = true;
}
