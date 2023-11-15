<?php

namespace Fintech\Business\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceStateRequest extends FormRequest
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
            'role_id' => ['array', 'required'],
            'service_id' => ['integer', 'required'],
            'service_slug' => ['string', 'required'],
            'source_country_id' => ['array', 'required'],
            'destination_country_id' => ['array', 'required'],
            'service_vendor_id' => ['integer', 'required'],
            'service_state_data' => ['array', 'required'],
            'service_state_data.*.lower_limit' => ['string', 'required'],
            'service_state_data.*.higher_limit' => ['string', 'required'],
            'service_state_data.*.local_currency_higher_limit' => ['string', 'required'],
            'service_state_data.*.charge' => ['string', 'required'],
            'service_state_data.*.discount' => ['string', 'required'],
            'service_state_data.*.commission' => ['string', 'required'],
            'service_state_data.*.cost' => ['string', 'required'],
            'service_state_data.*.charge_refund' => ['string', 'required'],
            'service_state_data.*.discount_refund' => ['string', 'required'],
            'service_state_data.*.commission_refund' => ['string', 'required'],
            'enabled' => ['boolean', 'nullable'],
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
