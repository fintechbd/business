<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Facades\Business;
use Fintech\Business\Http\Requests\ImportServiceRequest;
use Fintech\Business\Http\Requests\IndexServiceRequest;
use Fintech\Business\Http\Requests\ServiceCurrencyRateRequest;
use Fintech\Business\Http\Requests\StoreServiceRequest;
use Fintech\Business\Http\Requests\UpdateServiceRequest;
use Fintech\Business\Http\Resources\ServiceCollection;
use Fintech\Business\Http\Resources\ServiceCostResource;
use Fintech\Business\Http\Resources\ServiceResource;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class ServiceController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to Service
 *
 * @lrd:end
 */
class ServiceController extends Controller
{
    use ApiResponseTrait;

    /**
     * @lrd:start
     * Return a listing of the *Service* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexServiceRequest $request): ServiceCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $servicePaginate = Business::service()->list($inputs);

            return new ServiceCollection($servicePaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a new *Service* resource in storage.
     *
     * @lrd:end
     *
     * @throws StoreOperationException
     */
    public function store(StoreServiceRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $service = Business::service()->create($inputs);

            if (! $service) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_model'));
            }

            return $this->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service']),
                'id' => $service->id,
            ]);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Return a specified *Service* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): ServiceResource|JsonResponse
    {
        try {

            $service = Business::service()->find($id);

            if (! $service) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_model'), $id);
            }

            return new ServiceResource($service);

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Update a specified *Service* resource using id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateServiceRequest $request, string|int $id): JsonResponse
    {
        try {

            $service = Business::service()->find($id);

            if (! $service) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_model'), $id);
            }

            $inputs = $request->validated();

            if (! Business::service()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_model'), $id);
            }

            return $this->updated(__('core::messages.resource.updated', ['model' => 'Service']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *Service* resource using id.
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

            $service = Business::service()->find($id);

            if (! $service) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_model'), $id);
            }

            if (! Business::service()->destroy($id)) {

                throw (new DeleteOperationException())->setModel(config('fintech.business.service_model'), $id);
            }

            return $this->deleted(__('core::messages.resource.deleted', ['model' => 'Service']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Restore the specified *Service* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     *
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $service = Business::service()->find($id, true);

            if (! $service) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_model'), $id);
            }

            if (! Business::service()->restore($id)) {

                throw (new RestoreOperationException())->setModel(config('fintech.business.service_model'), $id);
            }

            return $this->restored(__('core::messages.resource.restored', ['model' => 'Service']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *Service* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexServiceRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $servicePaginate = Business::service()->export($inputs);

            return $this->exported(__('core::messages.resource.exported', ['model' => 'Service']));

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *Service* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @return ServiceCollection|JsonResponse
     */
    public function import(ImportServiceRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $servicePaginate = Business::service()->list($inputs);

            return new ServiceCollection($servicePaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    public function cost(ServiceCurrencyRateRequest $request): JsonResponse|ServiceCostResource
    {
        $inputs = $request->all();

        try {

            $servicePaginate = Business::currencyRate()->list($inputs);

            return new ServiceCostResource($servicePaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
