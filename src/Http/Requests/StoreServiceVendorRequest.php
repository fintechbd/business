<?php

namespace Fintech\Business\Http\Requests;

use Fintech\Business\Models\ServiceVendor;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceVendorRequest extends FormRequest
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
        /** @phpstan-ignore-next-line */
        $service_vendor_id = (int) collect(request()->segments())->last(); //id of the resource
        $uniqueRule = 'unique:'.config('fintech.business.service_vendor_model', ServiceVendor::class).',service_vendor_slug,'.$service_vendor_id.',id,deleted_at,NULL';

        return [
            'service_vendor_name' => ['string', 'required', 'max:255'],
            'service_vendor_slug' => ['string', 'required', $uniqueRule],
            'logo_png' => ['string', 'nullable'],
            'logo_svg' => ['string', 'nullable'],
            'service_vendor_data' => ['nullable', 'array'],
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
