<?php

namespace App\Http\Requests;

use App\Enums\Period;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class UpdateBudgetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'uuid',
                'exists:categories,id',
                Rule::unique('budgets')->where('user_id', $this->user()->id)->where('period', $this->input('period'))->ignore($this->route('id'))
            ],
            'limit_amount' => ['required', 'decimal:0,2'],
            'period' => ['required', new Enum(Period::class)]
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.unique' => 'You already have a budget set for this category.',
        ];
    }
}
