<?php

namespace Fintech\Business\Http\Requests;

use Fintech\Core\Traits\RestApi\HasPaginateQuery;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ServiceTypeListRequest extends FormRequest
{
    use HasPaginateQuery;

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
            'user_id' => ['integer', 'nullable'],
            'role_id' => ['integer', 'nullable'],
            'service_type_parent_slug' => ['string', 'nullable', 'exists:service_types,service_type_slug'],
            'service_type_parent_id' => ['integer', 'nullable'],
            'source_country_id' => ['integer', 'nullable', 'master_currency'],
            'destination_country_id' => ['integer', 'nullable', 'master_currency'],
            'visible_android_app' => ['string', 'required_without_all:visible_ios_app,visible_website,visible_website_kommerce'],
            'visible_ios_app' => ['string', 'required_without_all:visible_android_app,visible_website,visible_website_kommerce'],
            'visible_website' => ['string', 'required_without_all:visible_ios_app,visible_android_app,visible_website_kommerce'],
            'visible_website_kommerce' => ['string', 'required_without_all:visible_ios_app,visible_android_app,visible_website'],
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
