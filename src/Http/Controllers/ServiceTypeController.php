<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Facades\Business;
use Fintech\Business\Http\Requests\ImportServiceTypeRequest;
use Fintech\Business\Http\Requests\IndexServiceTypeRequest;
use Fintech\Business\Http\Requests\StoreServiceTypeRequest;
use Fintech\Business\Http\Requests\UpdateServiceTypeRequest;
use Fintech\Business\Http\Resources\ServiceTypeCollection;
use Fintech\Business\Http\Resources\ServiceTypeResource;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class ServiceTypeController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to ServiceType
 *
 * @lrd:end
 */
class ServiceTypeController extends Controller
{
    use ApiResponseTrait;

    /**
     * @lrd:start
     * Return a listing of the *ServiceType* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexServiceTypeRequest $request): ServiceTypeCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceTypePaginate = Business::serviceType()->list($inputs);

            return new ServiceTypeCollection($serviceTypePaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a new *ServiceType* resource in storage.
     *
     * @lrd:end
     *
     * @throws StoreOperationException
     */
    public function store(StoreServiceTypeRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceType = Business::serviceType()->create($inputs);

            if (! $serviceType) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_type_model'));
            }

            return $this->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service Type']),
                'id' => $serviceType->id,
            ]);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Return a specified *ServiceType* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): ServiceTypeResource|JsonResponse
    {
        try {

            $serviceType = Business::serviceType()->find($id);

            if (! $serviceType) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            return new ServiceTypeResource($serviceType);

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Update a specified *ServiceType* resource using id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateServiceTypeRequest $request, string|int $id): JsonResponse
    {
        try {

            $serviceType = Business::serviceType()->find($id);

            if (! $serviceType) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            $inputs = $request->validated();

            if (! Business::serviceType()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            return $this->updated(__('core::messages.resource.updated', ['model' => 'Service Type']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *ServiceType* resource using id.
     *
     * @lrd:end
     *
     * @return JsonResponse
     *
     * @throws ModelNotFoundException
     * @throws DeleteOperationException
     */
    public function destroy(string|int $id)
    {
        try {

            $serviceType = Business::serviceType()->find($id);

            if (! $serviceType) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            if (! Business::serviceType()->destroy($id)) {

                throw (new DeleteOperationException())->setModel(config('fintech.business.service_type_model'), $id);
            }

            return $this->deleted(__('core::messages.resource.deleted', ['model' => 'Service Type']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Restore the specified *ServiceType* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     *
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $serviceType = Business::serviceType()->find($id, true);

            if (! $serviceType) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_type_model'), $id);
            }

            if (! Business::serviceType()->restore($id)) {

                throw (new RestoreOperationException())->setModel(config('fintech.business.service_type_model'), $id);
            }

            return $this->restored(__('core::messages.resource.restored', ['model' => 'Service Type']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServiceType* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexServiceTypeRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceTypePaginate = Business::serviceType()->export($inputs);

            return $this->exported(__('core::messages.resource.exported', ['model' => 'Service Type']));

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServiceType* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @return ServiceTypeCollection|JsonResponse
     */
    public function import(ImportServiceTypeRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceTypePaginate = Business::serviceType()->list($inputs);

            return new ServiceTypeCollection($serviceTypePaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
