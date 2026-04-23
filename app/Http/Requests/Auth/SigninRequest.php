<?php

namespace App\Http\Requests\Auth;

use App\Models\CompanyModel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SigninRequest extends FormRequest
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
            'domain' => ['required', 'string', Rule::exists(CompanyModel::class, 'domain')],
            'username' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'domain' => 'Domain',
            'username' => 'Username',
            'password' => 'Password',
        ];
    }

    protected $stopOnFirstFailure = true;
}
