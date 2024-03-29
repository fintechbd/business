<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Facades\Business;
use Fintech\Business\Http\Requests\ServiceVendorServiceRequest;
use Fintech\Business\Http\Resources\ServiceVendorServiceResource;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ServiceVendorServiceController extends Controller
{
    use ApiResponseTrait;

    /**
     * @lrd:start
     * return services to a specified service vendor resource using id.
     *
     * @lrd:end
     */
    public function show(string|int $id): ServiceVendorServiceResource|JsonResponse
    {
        try {

            $serviceVendor = Business::serviceVendor()->find($id);

            if (! $serviceVendor) {
                throw (new ModelNotFoundException())->setModel(config('fintech.business.service_vendor_model'), $id);
            }

            return new ServiceVendorServiceResource($serviceVendor);

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Assign services to a specified service vendor resource using id.
     *
     * @lrd:end
     */
    public function update(ServiceVendorServiceRequest $request, string|int $id): JsonResponse
    {
        try {

            $serviceVendor = Business::serviceVendor()->find($id);

            if (! $serviceVendor) {
                throw (new ModelNotFoundException())->setModel(config('fintech.business.service_vendor_model'), $id);
            }

            $inputs = $request->validated();

            if (! Business::serviceVendor()->update($id, $inputs)) {

                throw (new UpdateOperationException())->setModel(config('fintech.business.service_vendor_model'), $id);
            }

            return $this->updated(__('business::messages.vendor.service_assigned', ['vendor' => strtolower($serviceVendor->name ?? 'N/A')]));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
