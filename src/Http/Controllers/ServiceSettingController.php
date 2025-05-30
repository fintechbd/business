<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Http\Requests\ImportServiceSettingRequest;
use Fintech\Business\Http\Requests\IndexServiceSettingRequest;
use Fintech\Business\Http\Requests\StoreServiceSettingRequest;
use Fintech\Business\Http\Requests\UpdateServiceSettingRequest;
use Fintech\Business\Http\Resources\ServiceSettingCollection;
use Fintech\Business\Http\Resources\ServiceSettingResource;
use Fintech\Business\Http\Resources\ServiceSettingTypeResource;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
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

            $serviceSettingPaginate = business()->serviceSetting()->list($inputs);

            return new ServiceSettingCollection($serviceSettingPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a new *ServiceSetting* resource in storage.
     *
     * @lrd:end
     */
    public function store(StoreServiceSettingRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceSetting = business()->serviceSetting()->create($inputs);

            if (! $serviceSetting) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_setting_model'));
            }

            return response()->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service Setting']),
                'id' => $serviceSetting->getKey(),
            ]);

        } catch (Exception $exception) {

            return response()->failed($exception);
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

            $serviceSetting = business()->serviceSetting()->find($id);

            if (! $serviceSetting) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            return new ServiceSettingResource($serviceSetting);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Update a specified *ServiceSetting* resource using id.
     *
     * @lrd:end
     */
    public function update(UpdateServiceSettingRequest $request, string|int $id): JsonResponse
    {
        try {

            $serviceSetting = business()->serviceSetting()->find($id);

            if (! $serviceSetting) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            $inputs = $request->validated();

            if (! business()->serviceSetting()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            return response()->updated(__('core::messages.resource.updated', ['model' => 'Service Setting']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *ServiceSetting* resource using id.
     *
     * @lrd:end
     */
    public function destroy(string|int $id): JsonResponse
    {
        try {

            $serviceSetting = business()->serviceSetting()->find($id);

            if (! $serviceSetting) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            if (! business()->serviceSetting()->destroy($id)) {

                throw (new DeleteOperationException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            return response()->deleted(__('core::messages.resource.deleted', ['model' => 'Service Setting']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Restore the specified *ServiceSetting* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     */
    public function restore(string|int $id): JsonResponse
    {
        try {

            $serviceSetting = business()->serviceSetting()->find($id, true);

            if (! $serviceSetting) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            if (! business()->serviceSetting()->restore($id)) {

                throw (new RestoreOperationException)->setModel(config('fintech.business.service_setting_model'), $id);
            }

            return response()->restored(__('core::messages.resource.restored', ['model' => 'Service Setting']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create an exportable list of the *ServiceSetting* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexServiceSettingRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            // $serviceSettingPaginate = business()->serviceSetting()->export($inputs);
            business()->serviceSetting()->export($inputs);

            return response()->exported(__('core::messages.resource.exported', ['model' => 'Service Setting']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create an exportable list of the *ServiceSetting* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function import(ImportServiceSettingRequest $request): ServiceSettingCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceSettingPaginate = business()->serviceSetting()->list($inputs);

            return new ServiceSettingCollection($serviceSettingPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    public function serviceSettingTypes(): ServiceSettingTypeResource|JsonResponse
    {
        try {
            $serviceSettingTypes = config('fintech.business.service_setting_types');

            return new ServiceSettingTypeResource($serviceSettingTypes);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    public function serviceSettingTypeFields(): ServiceSettingTypeResource|JsonResponse
    {
        try {
            $serviceSettingTypeFields = config('fintech.business.service_setting_type_fields');

            return new ServiceSettingTypeResource($serviceSettingTypeFields);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }
}
