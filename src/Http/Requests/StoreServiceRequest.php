<?php

namespace Fintech\Business\Http\Requests;

use Fintech\Business\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
        $service_id = (int) collect(request()->segments())->last(); //id of the resource
        $uniqueRule = 'unique:'.config('fintech.business.service_model', Service::class).',service_name,'.$service_id.',id,service_type_id,'.$this->input('service_type_id').',service_vendor_id,'.$this->input('service_vendor_id').',deleted_at,NULL';

        return [
            'service_type_id' => ['integer', 'required'],
            'service_vendor_id' => ['integer', 'required'],
            'service_name' => ['string', 'required', 'max:255', $uniqueRule],
            'service_notification' => ['string', 'nullable'],
            'service_delay' => ['string', 'nullable'],
            'service_stat_policy' => ['string', 'nullable'],
            'service_serial' => ['integer', 'required'],
            'service_data' => ['array', 'required'],
            'service_data.service_logo_svg.*' => ['string', 'nullable'],
            'service_data.service_logo_png.*' => ['string', 'nullable'],
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
