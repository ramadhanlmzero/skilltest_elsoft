<?php

namespace App\Http\Requests\StockIssue;

use App\Models\StockIssueModel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteStockIssueRequest extends FormRequest
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
            'Oid' => ['required', 'uuid', Rule::exists(StockIssueModel::class, 'id')],
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
        ];
    }

    protected $stopOnFirstFailure = true;
}
