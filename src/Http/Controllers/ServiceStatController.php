<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Http\Requests\ImportServiceStatRequest;
use Fintech\Business\Http\Requests\IndexServiceStatRequest;
use Fintech\Business\Http\Requests\StoreServiceStatRequest;
use Fintech\Business\Http\Requests\UpdateServiceStatRequest;
use Fintech\Business\Http\Resources\ServiceStatCollection;
use Fintech\Business\Http\Resources\ServiceStatResource;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Http\Requests\DropDownRequest;
use Fintech\Core\Http\Resources\DropDownCollection;
use Fintech\MetaData\Facades\MetaData;
use Fintech\MetaData\Http\Resources\CountryCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class ServiceStatController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to ServiceStat
 *
 * @lrd:end
 */
class ServiceStatController extends Controller
{
    /**
     * @lrd:start
     * Return a listing of the *ServiceStat* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexServiceStatRequest $request): ServiceStatCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $serviceStatPaginate = business()->serviceStat()->list($inputs);

            return new ServiceStatCollection($serviceStatPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a new *ServiceStat* resource in storage.
     *
     * @lrd:end
     */
    public function store(StoreServiceStatRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();
            $serviceStat = business()->serviceStat()->customStore($inputs);

            if (! $serviceStat) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_stat_model'));
            }

            return response()->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service Stat']),
                'id' => $serviceStat,
            ]);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Return a specified *ServiceStat* resource found by id.
     *
     * @lrd:end
     */
    public function show(string|int $id): ServiceStatResource|JsonResponse
    {
        try {

            $serviceStat = business()->serviceStat()->find($id);

            if (! $serviceStat) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_stat_model'), $id);
            }

            return new ServiceStatResource($serviceStat);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Update a specified *ServiceStat* resource using id.
     *
     * @lrd:end
     */
    public function update(UpdateServiceStatRequest $request, string|int $id): JsonResponse
    {
        try {

            $serviceStat = business()->serviceStat()->find($id);

            if (! $serviceStat) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_stat_model'), $id);
            }

            $inputs = $request->validated();

            if (! business()->serviceStat()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_stat_model'), $id);
            }

            return response()->updated(__('core::messages.resource.updated', ['model' => 'Service Stat']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *ServiceStat* resource using id.
     *
     * @lrd:end
     */
    public function destroy(string|int $id): JsonResponse
    {
        try {

            $serviceStat = business()->serviceStat()->find($id);

            if (! $serviceStat) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_stat_model'), $id);
            }

            if (! business()->serviceStat()->destroy($id)) {

                throw (new DeleteOperationException)->setModel(config('fintech.business.service_stat_model'), $id);
            }

            return response()->deleted(__('core::messages.resource.deleted', ['model' => 'Service Stat']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Restore the specified *ServiceStat* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     */
    public function restore(string|int $id): JsonResponse
    {
        try {

            $serviceStat = business()->serviceStat()->find($id, true);

            if (! $serviceStat) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_stat_model'), $id);
            }

            if (! business()->serviceStat()->restore($id)) {

                throw (new RestoreOperationException)->setModel(config('fintech.business.service_stat_model'), $id);
            }

            return response()->restored(__('core::messages.resource.restored', ['model' => 'Service Stat']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create an exportable list of the *ServiceStat* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexServiceStatRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            business()->serviceStat()->export($inputs);

            return response()->exported(__('core::messages.resource.exported', ['model' => 'Service Stat']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create an exportable list of the *ServiceStat* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function import(ImportServiceStatRequest $request): JsonResponse|ServiceStatCollection
    {
        try {
            $inputs = $request->validated();

            $serviceStatPaginate = business()->serviceStat()->list($inputs);

            return new ServiceStatCollection($serviceStatPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    public function serviceStatWiseCountry(Request $request): CountryCollection|JsonResponse
    {
        try {
            $inputs = $request->all();
            $inputs['sort'] = 'destination_country_id';
            $inputs['paginate'] = false;

            $destination_countries = business()->serviceStat()->list($inputs)->toArray();

            $list = array_unique(array_column($destination_countries, $inputs['sort']));
            $countries = MetaData::country()->list(['in_array_country_id' => array_values($list)]);

            return new CountryCollection($countries);
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

            $entries = business()->serviceStat()->list($filters)->map(function ($entry) use ($label, $attribute) {
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
