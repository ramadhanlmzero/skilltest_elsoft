<?php

namespace App\Http\Requests\StockIssue;

use App\Models\StockIssueDetailModel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteStockIssueDetailRequest extends FormRequest
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
            'Oid' => ['required', 'uuid', Rule::exists(StockIssueDetailModel::class, 'id')],
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
