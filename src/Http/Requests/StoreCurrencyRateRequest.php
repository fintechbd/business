<?php

namespace Fintech\Business\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'source_country_id' => ['required', 'integer', 'min:1', 'master_currency', 'master_currency'],
            'destination_country_id' => ['required', 'integer', 'min:1', 'master_currency', 'master_currency'],
            'service_id' => ['required', 'integer', 'min:1'],
            'rate' => ['required', 'numeric'],
            'is_default' => ['nullable', 'boolean'],
            'currency_rate_data' => ['nullable', 'array'],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
