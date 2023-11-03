<?php

namespace Fintech\Business\Http\Requests;

use Fintech\Business\Models\ServiceType;
use Illuminate\Foundation\Http\FormRequest;

class ImportServiceTypeRequest extends FormRequest
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
        $service_type_id = (int) collect(request()->segments())->last(); //id of the resource
        $uniqueRule = 'unique:'.config('fintech.business.service_type_model', ServiceType::class).',service_setting_field_name,'.$service_type_id.',id,service_setting_type,'.$this->input('service_setting_type').',deleted_at,NULL';

        return [
            'service_type_parent_id' => ['integer', 'nullable'],
            'service_type_name' => ['string', 'required', 'max:255'],
            'service_type_is_parent' => ['string', 'required', $uniqueRule],
            //'service_type_step' => ['integer', 'required'],
            'service_type_data' => ['array', 'required'],
            'service_type_logo_svg.*' => ['string', 'nullable'],
            'service_type_logo_png.*' => ['string', 'nullable'],
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
