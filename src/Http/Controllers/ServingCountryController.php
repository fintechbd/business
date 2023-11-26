<?php

namespace Fintech\Business\Http\Controllers;

use Fintech\Core\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ServingCountryController extends Controller
{
    use ApiResponseTrait;

    public function __invoke(): \Fintech\MetaData\Http\Resources\CountryCollection|JsonResponse
    {
        try {

            $inputs['paginate'] = false;
            $inputs['is_serving'] = true;

            $countryPaginate = \Fintech\MetaData\Facades\MetaData::country()->list($inputs);

            return new \Fintech\MetaData\Http\Resources\CountryCollection($countryPaginate);

        } catch (\Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
