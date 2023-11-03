<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Facades\Business;
use Fintech\Business\Http\Requests\ImportServiceSettingRequest;
use Fintech\Business\Http\Requests\IndexServiceSettingRequest;
use Fintech\Business\Http\Requests\StoreServiceSettingRequest;
use Fintech\Business\Http\Requests\UpdateServiceSettingRequest;
use Fintech\Business\Http\Resources\ServiceSettingCollection;
use Fintech\Business\Http\Resources\ServiceSettingResource;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class ServiceSettingController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to ServiceSetting
 *
 * @lrd:end
 */
class ServiceSettingController extends Controller
{
    use ApiResponseTrait;

    /**
     * @lrd:start
     * Return a listing of the *ServiceSetting* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexServiceSettingRequest $request): ServiceSettingCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceSettingPaginate = Business::serviceSetting()->list($inputs);

            return new ServiceSettingCollection($serviceSettingPaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a new *ServiceSetting* resource in storage.
     *
     * @lrd:end
     *
     * @throws StoreOperationException
     */
    public function store(StoreServiceSettingRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceSetting = Business::serviceSetting()->create($inputs);

            if (! $serviceSetting) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_setting_model'));
            }

            return $this->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service Setting']),
                'id' => $serviceSetting->id,
            ]);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Return a specified *ServiceSetting* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): ServiceSettingResource|JsonResponse
    {
        try {

            $serviceSetting = Business::serviceSetting()->find($id);

            if (! $serviceSetting) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            return new ServiceSettingResource($serviceSetting);

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Update a specified *ServiceSetting* resource using id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateServiceSettingRequest $request, string|int $id): JsonResponse
    {
        try {

            $serviceSetting = Business::serviceSetting()->find($id);

            if (! $serviceSetting) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            $inputs = $request->validated();

            if (! Business::serviceSetting()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            return $this->updated(__('core::messages.resource.updated', ['model' => 'Service Setting']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *ServiceSetting* resource using id.
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

            $serviceSetting = Business::serviceSetting()->find($id);

            if (! $serviceSetting) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            if (! Business::serviceSetting()->destroy($id)) {

                throw (new DeleteOperationException())->setModel(config('fintech.business.service_setting_model'), $id);
            }

            return $this->deleted(__('core::messages.resource.deleted', ['model' => 'Service Setting']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Restore the specified *ServiceSetting* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     *
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $serviceSetting = Business::serviceSetting()->find($id, true);

            if (! $serviceSetting) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            if (! Business::serviceSetting()->restore($id)) {

                throw (new RestoreOperationException())->setModel(config('fintech.business.service_setting_model'), $id);
            }

            return $this->restored(__('core::messages.resource.restored', ['model' => 'Service Setting']));

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServiceSetting* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexServiceSettingRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceSettingPaginate = Business::serviceSetting()->export($inputs);

            return $this->exported(__('core::messages.resource.exported', ['model' => 'Service Setting']));

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServiceSetting* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @return ServiceSettingCollection|JsonResponse
     */
    public function import(ImportServiceSettingRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceSettingPaginate = Business::serviceSetting()->list($inputs);

            return new ServiceSettingCollection($serviceSettingPaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}