<?php

namespace Fintech\Business\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceStateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'role_id' => ['integer', 'required'],
            'service_id' => ['integer', 'required'],
            'service_slug' => ['string', 'required'],
            'present_country_id' => ['integer', 'required'],
            'permanent_country_id' => ['integer', 'required'],
            'service_vendor_id' => ['integer', 'required'],
            'service_state_data' => ['array', 'required'],
            'enabled' => ['boolean', 'nullable', 'min:1'],
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
