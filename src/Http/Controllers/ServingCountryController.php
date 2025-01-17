<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\MetaData\Facades\MetaData;
use Fintech\MetaData\Http\Resources\CountryCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ServingCountryController extends Controller
{
    public function __invoke(): CountryCollection|JsonResponse
    {
        try {

            $inputs['paginate'] = false;
            $inputs['is_serving'] = true;

            $countryPaginate = MetaData::country()->list($inputs);

            return new CountryCollection($countryPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }
}
