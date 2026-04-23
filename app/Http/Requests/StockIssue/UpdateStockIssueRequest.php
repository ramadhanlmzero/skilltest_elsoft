<?php

namespace App\Http\Requests\StockIssue;

use App\Models\StockIssueModel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateStockIssueRequest extends CreateStockIssueRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Oid' => ['required', 'uuid', Rule::exists(StockIssueModel::class, 'id')],
            ...parent::rules(),
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->route('oid')) {
            $this->merge(['Oid' => $this->route('oid')]);
        }
    }

    public function attributes(): array
    {
        return [
            'Oid' => 'Oid',
            ...parent::attributes(),
        ];
    }

    protected $stopOnFirstFailure = true;
}
