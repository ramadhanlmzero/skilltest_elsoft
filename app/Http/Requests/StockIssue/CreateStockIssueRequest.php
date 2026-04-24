<?php

namespace App\Http\Requests\StockIssue;

use App\Models\AccountModel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStockIssueRequest extends FormRequest
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
            'Date' => ['required', 'date_format:Y-m-d'],
            'Account' => [
                'required',
                'uuid',
                Rule::exists(AccountModel::class, 'id'),
                function (string $attribute, mixed $value, Closure $fail): void {
                    $companyId = (string) ($this->user()?->company_id ?? '');

                    if ($companyId === '') {
                        $fail('Company user not found.');

                        return;
                    }

                    $exists = AccountModel::query()
                        ->whereKey((string) $value)
                        ->where('company_id', $companyId)
                        ->exists();

                    if (! $exists) {
                        $fail($attribute.' tidak sesuai dengan company yang dipilih.');
                    }
                },
            ],
            'Note' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'Date' => 'Date',
            'Account' => 'Account',
            'Note' => 'Note',
        ];
    }

    protected $stopOnFirstFailure = true;
}
