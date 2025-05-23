<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Http\Requests\CountryServiceRequest;
use Fintech\Business\Http\Resources\CountryServiceResource;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\MetaData\Facades\MetaData;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CountryServiceController extends Controller
{
    /**
     * @lrd:start
     * return services to a specified role resource using id.
     *
     * @lrd:end
     */
    public function show(string|int $id): CountryServiceResource|JsonResponse
    {
        try {

            $country = MetaData::country()->find($id);

            if (! $country) {
                throw (new ModelNotFoundException)->setModel(config('fintech.metadata.country_model'), $id);
            }

            return new CountryServiceResource($country);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Assign services to a specified role resource using id.
     *
     * @lrd:end
     */
    public function update(CountryServiceRequest $request, string|int $id): JsonResponse
    {
        try {

            $country = MetaData::country()->find($id);

            if (! $country) {
                throw (new ModelNotFoundException)->setModel(config('fintech.metadata.country_model'), $id);
            }

            $inputs = $request->validated();

            if (! MetaData::country()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.metadata.country_model'), $id);
            }

            return response()->updated(__('business::messages.country.service_assigned', ['country' => strtolower($country->name ?? 'N/A')]));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }
}
