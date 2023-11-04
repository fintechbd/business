<?php

namespace Fintech\Business\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChargeBreakDownRequest extends FormRequest
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
            'service_stat_id' => ['integer', 'required'],
            'service_slug' => ['string', 'required'],
            'charge_break_down_lower' => ['double', 'required'],
            'charge_break_down_higher' => ['double', 'required'],
            'charge_break_down_charge' => ['double', 'required'],
            'charge_break_down_discount' => ['double', 'required'],
            'charge_break_down_commission' => ['double', 'required'],
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
