<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Http\Requests\ImportServicePackageRequest;
use Fintech\Business\Http\Requests\IndexServicePackageRequest;
use Fintech\Business\Http\Requests\StoreServicePackageRequest;
use Fintech\Business\Http\Requests\UpdateServicePackageRequest;
use Fintech\Business\Http\Resources\ServicePackageCollection;
use Fintech\Business\Http\Resources\ServicePackageResource;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Http\Requests\DropDownRequest;
use Fintech\Core\Http\Resources\DropDownCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class ServicePackageController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to ServicePackage
 *
 * @lrd:end
 */
class ServicePackageController extends Controller
{
    /**
     * @lrd:start
     * Return a listing of the *ServicePackage* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexServicePackageRequest $request): ServicePackageCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $servicePackagePaginate = business()->servicePackage()->list($inputs);

            return new ServicePackageCollection($servicePackagePaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a new *ServicePackage* resource in storage.
     *
     * @lrd:end
     *
     * @throws StoreOperationException
     */
    public function store(StoreServicePackageRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $servicePackage = business()->servicePackage()->create($inputs);

            if (! $servicePackage) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_package_model'));
            }

            return response()->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service Package']),
                'id' => $servicePackage->id,
            ]);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Return a specified *ServicePackage* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): ServicePackageResource|JsonResponse
    {
        try {

            $servicePackage = business()->servicePackage()->find($id);

            if (! $servicePackage) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_package_model'), $id);
            }

            return new ServicePackageResource($servicePackage);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Update a specified *ServicePackage* resource using id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateServicePackageRequest $request, string|int $id): JsonResponse
    {
        try {

            $servicePackage = business()->servicePackage()->find($id);

            if (! $servicePackage) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_package_model'), $id);
            }

            $inputs = $request->validated();

            if (! business()->servicePackage()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_package_model'), $id);
            }

            return response()->updated(__('core::messages.resource.updated', ['model' => 'Service Package']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *ServicePackage* resource using id.
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

            $servicePackage = business()->servicePackage()->find($id);

            if (! $servicePackage) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_package_model'), $id);
            }

            if (! business()->servicePackage()->destroy($id)) {

                throw (new DeleteOperationException)->setModel(config('fintech.business.service_package_model'), $id);
            }

            return response()->deleted(__('core::messages.resource.deleted', ['model' => 'Service Package']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Restore the specified *ServicePackage* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     *
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $servicePackage = business()->servicePackage()->find($id, true);

            if (! $servicePackage) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_package_model'), $id);
            }

            if (! business()->servicePackage()->restore($id)) {

                throw (new RestoreOperationException)->setModel(config('fintech.business.service_package_model'), $id);
            }

            return response()->restored(__('core::messages.resource.restored', ['model' => 'Service Package']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServicePackage* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @return ServicePackageCollection|JsonResponse
     */
    public function import(ImportServicePackageRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $servicePackagePaginate = business()->servicePackage()->list($inputs);

            return new ServicePackageCollection($servicePackagePaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * This endpoint allow admin user to update the service package list of a specific service
     *
     * @lrd:end
     */
    public function sync(IndexServicePackageRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $servicePackagePaginate = business()->servicePackage()->export($inputs);

            return response()->exported(__('core::messages.resource.exported', ['model' => 'Service Package']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *ServicePackage* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexServicePackageRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $servicePackagePaginate = business()->servicePackage()->export($inputs);

            return response()->exported(__('core::messages.resource.exported', ['model' => 'Service Package']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @LRDparam country_id required|integer|min:1
     * @LRDparam state_id required|integer|min:1
     */
    public function dropdown(DropDownRequest $request): DropDownCollection|JsonResponse
    {
        try {
            $filters = $request->all();

            $filters['enabled'] = $filters['enabled'] ?? true;

            $label = 'name';

            $attribute = 'id';

            if (! empty($filters['label'])) {
                $label = $filters['label'];
                unset($filters['label']);
            }

            if (! empty($filters['attribute'])) {
                $attribute = $filters['attribute'];
                unset($filters['attribute']);
            }

            $entries = business()->servicePackage()->list($filters)->map(function ($entry) use ($label, $attribute) {
                return [
                    'attribute' => $entry->{$attribute} ?? 'id',
                    'label' => $entry->{$label} ?? 'name',
                ];
            })->toArray();

            return new DropDownCollection($entries);

        } catch (Exception $exception) {
            return response()->failed($exception);
        }
    }
}
