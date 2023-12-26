<?php

namespace Fintech\Business\Http\Controllers;

use Exception;
use Fintech\Business\Facades\Business;
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
use Fintech\Core\Traits\ApiResponseTrait;
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
    use ApiResponseTrait;

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

            $serviceStatPaginate = Business::serviceStat()->list($inputs);

            return new ServiceStatCollection($serviceStatPaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
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
            $serviceStat = Business::serviceStat()->customStore($inputs);

            if (! $serviceStat) {
                throw (new StoreOperationException)->setModel(config('fintech.business.service_stat_model'));
            }

            return $this->created([
                'message' => __('core::messages.resource.created', ['model' => 'Service State']),
                'id' => $serviceStat,
            ]);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Return a specified *ServiceStat* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): ServiceStatResource|JsonResponse
    {
        try {

            $serviceStat = Business::serviceStat()->find($id);

            if (! $serviceStat) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_stat_model'), $id);
            }

            return new ServiceStatResource($serviceStat);

        } catch (ModelNotFoundException $exception) {

            return $this->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Update a specified *ServiceStat* resource using id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function update(UpdateServiceStatRequest $request, string|int $id): JsonResponse
    {
        try {

            $serviceStat = Business::serviceStat()->find($id);

            if (! $serviceStat) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_stat_model'), $id);
            }

            $inputs = $request->validated();

            if (! Business::serviceStat()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.business.service_stat_model'), $id);
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
     * Soft delete a specified *ServiceStat* resource using id.
     *
     * @lrd:end
     *
     * @param string|int $id
     * @return JsonResponse
     *
     */
    public function destroy(string|int $id): JsonResponse
    {
        try {

            $serviceStat = Business::serviceStat()->find($id);

            if (! $serviceStat) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_stat_model'), $id);
            }

            if (! Business::serviceStat()->destroy($id)) {

                throw (new DeleteOperationException())->setModel(config('fintech.business.service_stat_model'), $id);
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
     * Restore the specified *ServiceStat* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     */
    public function restore(string|int $id): JsonResponse
    {
        try {

            $serviceStat = Business::serviceStat()->find($id, true);

            if (! $serviceStat) {
                throw (new ModelNotFoundException)->setModel(config('fintech.business.service_stat_model'), $id);
            }

            if (! Business::serviceStat()->restore($id)) {

                throw (new RestoreOperationException())->setModel(config('fintech.business.service_stat_model'), $id);
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
     * Create an exportable list of the *ServiceStat* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexServiceStatRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            //$serviceStatPaginate = Business::serviceStat()->export($inputs);
            Business::serviceStat()->export($inputs);

            return $this->exported(__('core::messages.resource.exported', ['model' => 'Service State']));

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @lrd:start
     * Create an exportable list of the *ServiceStat* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @param ImportServiceStatRequest $request
     * @return ServiceStatCollection|JsonResponse
     */
    public function import(ImportServiceStatRequest $request): JsonResponse|ServiceStatCollection
    {
        try {
            $inputs = $request->validated();

            $serviceStatPaginate = Business::serviceStat()->list($inputs);

            return new ServiceStatCollection($serviceStatPaginate);

        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return CountryCollection|JsonResponse
     */
    public function serviceStatWiseCountry(Request $request): CountryCollection|JsonResponse
    {
        try {
            $inputs = $request->all();
            $inputs['sort'] = 'destination_country_id';
            $inputs['paginate'] = false;

            $destination_countries = Business::serviceStat()->list($inputs)->toArray();

            $list = array_unique(array_column($destination_countries, $inputs['sort']));
            $countries = MetaData::country()->list(['in_array_country_id' => array_values($list)]);

            return new CountryCollection($countries);
        } catch (Exception $exception) {

            return $this->failed($exception->getMessage());
        }
    }
}
