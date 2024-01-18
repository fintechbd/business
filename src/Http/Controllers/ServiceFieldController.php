<?php

namespace Fintech\Business\Http\Controllers;
use Exception;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Traits\ApiResponseTrait;
use Fintech\Business\Facades\Business;
use Fintech\Business\Http\Resources\ServiceFieldResource;
use Fintech\Business\Http\Resources\ServiceFieldCollection;
use Fintech\Business\Http\Requests\ImportServiceFieldRequest;
use Fintech\Business\Http\Requests\StoreServiceFieldRequest;
use Fintech\Business\Http\Requests\UpdateServiceFieldRequest;
use Fintech\Business\Http\Requests\IndexServiceFieldRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class ServiceFieldController
 * @package Fintech\Business\Http\Controllers
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to ServiceField
 * @lrd:end
 *
 */

class ServiceFieldController extends Controller
{
    use ApiResponseTrait;

    /**
     * @lrd:start
     * Return a listing of the *ServiceField* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     * @lrd:end
     *
     * @param IndexServiceFieldRequest $request
     * @return ServiceFieldCollection|JsonResponse
     */
    public function index(IndexServiceFieldRequest $request): ServiceFieldCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceFieldPaginate = Business::serviceField()->list($inputs);

            return new ServiceFieldCollection($serviceFieldPaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a new *ServiceField* resource in storage.
     * @lrd:end
     *
     * @param StoreServiceFieldRequest $request
     * @return JsonResponse
     * @throws StoreOperationException
     */
    public function store(StoreServiceFieldRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceField = Business::serviceField()->create($inputs);

            if (!$serviceField) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_field_model'));
            }

            return $this->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service Field']),
                'id' => $serviceField->id
             ]);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Return a specified *ServiceField* resource found by id.
     * @lrd:end
     *
     * @param string|int $id
     * @return ServiceFieldResource|JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): ServiceFieldResource|JsonResponse
    {
        try {

            $serviceField = Business::serviceField()->find($id);

            if (!$serviceField) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_field_model'), $id);
            }

            return new ServiceFieldResource($serviceField);

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Update a specified *ServiceField* resource using id.
     * @lrd:end
     *
     * @param UpdateServiceFieldRequest $request
     * @param string|int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateServiceFieldRequest $request, string|int $id): JsonResponse
    {
        try {

            $serviceField = Business::serviceField()->find($id);

            if (!$serviceField) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_field_model'), $id);
            }

            $inputs = $request->validated();

            if (!Business::serviceField()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_field_model'), $id);
            }

            return $this->updated(__('core::messages.resource.updated', ['model' => 'Service Field']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *ServiceField* resource using id.
     * @lrd:end
     *
     * @param string|int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws DeleteOperationException
     */
    public function destroy(string|int $id)
    {
        try {

            $serviceField = Business::serviceField()->find($id);

            if (!$serviceField) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_field_model'), $id);
            }

            if (!Business::serviceField()->destroy($id)) {

                throw (new DeleteOperationException())->setModel(config('fintech.business.service_field_model'), $id);
            }

            return $this->deleted(__('core::messages.resource.deleted', ['model' => 'Service Field']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Restore the specified *ServiceField* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     * @lrd:end
     *
     * @param string|int $id
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $serviceField = Business::serviceField()->find($id, true);

            if (!$serviceField) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_field_model'), $id);
            }

            if (!Business::serviceField()->restore($id)) {

                throw (new RestoreOperationException())->setModel(config('fintech.business.service_field_model'), $id);
            }

            return $this->restored(__('core::messages.resource.restored', ['model' => 'Service Field']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServiceField* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @param IndexServiceFieldRequest $request
     * @return JsonResponse
     */
    public function export(IndexServiceFieldRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceFieldPaginate = Business::serviceField()->export($inputs);

            return $this->exported(__('core::messages.resource.exported', ['model' => 'Service Field']));

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServiceField* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @param ImportServiceFieldRequest $request
     * @return ServiceFieldCollection|JsonResponse
     */
    public function import(ImportServiceFieldRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceFieldPaginate = Business::serviceField()->list($inputs);

            return new ServiceFieldCollection($serviceFieldPaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
