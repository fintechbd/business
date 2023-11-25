<?php

namespace Fintech\Business\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Fintech\Business\Http\Requests\RoleServiceRequest;
use Fintech\Business\Http\Resources\CountryServiceResource;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CountryServiceController extends Controller
{
    use ApiResponseTrait;

    /**
     * @lrd:start
     * return services to a specified role resource using id.
     *
     * @lrd:end
     */
    public function show(string|int $id): CountryServiceResource|JsonResponse
    {
        try {

            $country = \Fintech\MetaData\Facades\MetaData::country()->find($id);

            if (!$country) {
                throw (new ModelNotFoundException())->setModel(config('fintech.metadata.country_model'), $id);
            }

            return new CountryServiceResource($country);

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Assign services to a specified role resource using id.
     *
     * @lrd:end
     */
    public function update(RoleServiceRequest $request, string|int $id): JsonResponse
    {
        try {

            $country = \Fintech\MetaData\Facades\MetaData::country()->find($id);

            if (!$country) {
                throw (new ModelNotFoundException())->setModel(config('fintech.auth.country_model'), $id);
            }

            $inputs = $request->validated();

            if (!\Fintech\MetaData\Facades\MetaData::country()->update($id, $inputs)) {

                throw (new UpdateOperationException())->setModel(config('fintech.auth.country_model'), $id);
            }

            return $this->updated(__('auth::messages.role.service_assigned', ['role' => strtolower($country->name ?? 'N/A')]));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
