<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Facades\Business;
use Fintech\Business\Http\Requests\ImportServiceStateRequest;
use Fintech\Business\Http\Requests\IndexServiceStateRequest;
use Fintech\Business\Http\Requests\StoreServiceStateRequest;
use Fintech\Business\Http\Requests\UpdateServiceStateRequest;
use Fintech\Business\Http\Resources\ServiceStateCollection;
use Fintech\Business\Http\Resources\ServiceStateResource;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class ServiceStateController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to ServiceState
 *
 * @lrd:end
 */
class ServiceStateController extends Controller
{
    use ApiResponseTrait;

    /**
     * @lrd:start
     * Return a listing of the *ServiceState* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexServiceStateRequest $request): ServiceStateCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceStatePaginate = Business::serviceState()->list($inputs);

            return new ServiceStateCollection($serviceStatePaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a new *ServiceState* resource in storage.
     *
     * @lrd:end
     *
     * @throws StoreOperationException
     */
    public function store(StoreServiceStateRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();
            $serviceState = Business::serviceState()->customStore($inputs);

            if (! $serviceState) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_state_model'));
            }

            return $this->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service State']),
                'id' => $serviceState,
            ]);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Return a specified *ServiceState* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): ServiceStateResource|JsonResponse
    {
        try {

            $serviceState = Business::serviceState()->find($id);

            if (! $serviceState) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_state_model'), $id);
            }

            return new ServiceStateResource($serviceState);

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Update a specified *ServiceState* resource using id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateServiceStateRequest $request, string|int $id): JsonResponse
    {
        try {

            $serviceState = Business::serviceState()->find($id);

            if (! $serviceState) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_state_model'), $id);
            }

            $inputs = $request->validated();

            if (! Business::serviceState()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_state_model'), $id);
            }

            return $this->updated(__('core::messages.resource.updated', ['model' => 'Service State']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *ServiceState* resource using id.
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

            $serviceState = Business::serviceState()->find($id);

            if (! $serviceState) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_state_model'), $id);
            }

            if (! Business::serviceState()->destroy($id)) {

                throw (new DeleteOperationException())->setModel(config('fintech.business.service_state_model'), $id);
            }

            return $this->deleted(__('core::messages.resource.deleted', ['model' => 'Service State']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Restore the specified *ServiceState* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     *
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $serviceState = Business::serviceState()->find($id, true);

            if (! $serviceState) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_state_model'), $id);
            }

            if (! Business::serviceState()->restore($id)) {

                throw (new RestoreOperationException())->setModel(config('fintech.business.service_state_model'), $id);
            }

            return $this->restored(__('core::messages.resource.restored', ['model' => 'Service State']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServiceState* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexServiceStateRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceStatePaginate = Business::serviceState()->export($inputs);

            return $this->exported(__('core::messages.resource.exported', ['model' => 'Service State']));

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServiceState* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @return ServiceStateCollection|JsonResponse
     */
    public function import(ImportServiceStateRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceStatePaginate = Business::serviceState()->list($inputs);

            return new ServiceStateCollection($serviceStatePaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
