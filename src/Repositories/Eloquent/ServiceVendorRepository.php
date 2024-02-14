<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\ServiceVendorRepository as InterfacesServiceVendorRepository;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use InvalidArgumentException;

/**
 * Class ServiceVendorRepository
 */
class ServiceVendorRepository extends EloquentRepository implements InterfacesServiceVendorRepository
{
    public function __construct()
    {
        $model = app(config('fintech.business.service_vendor_model', \Fintech\Business\Models\ServiceVendor::class));

        if (! $model instanceof Model) {
            throw new InvalidArgumentException("Eloquent repository require model class to be `Illuminate\Database\Eloquent\Model` instance.");
        }

        $this->model = $model;
    }

    /**
     * return a list or pagination of items from
     * filtered options
     *
     * @return Paginator|Collection
     */
    public function list(array $filters = [])
    {
        $query = $this->model->newQuery();

        //Searching
        if (! empty($filters['search'])) {
            if (is_numeric($filters['search'])) {
                $query->where($this->model->getKeyName(), 'like', "%{$filters['search']}%");
            } else {
                $query->where('name', 'like', "%{$filters['search']}%");
            }
        }

        //Display Trashed
        if (! empty($filters['trashed'])) {
            $query->onlyTrashed();
        }

        if (! empty($filters['service_vendor_slug'])) {
            $query->where('service_vendor_slug', 'like', "%{$filters['service_vendor_slug']}%");
        }

        if (! empty($filters['service_id_array'])) {
            $query->select('service_vendors.*')
                ->join('service_service_vendor', function (JoinClause $joinClause) use (&$filters) {
                    return $joinClause->on('service_vendors.id', '=', 'service_service_vendor.service_vendor_id')
                        ->whereIn('service_service_vendor.service_id', $filters['service_id_array']);
                });
        }

        //Handle Sorting
        $query->orderBy($filters['sort'] ?? $this->model->getKeyName(), $filters['dir'] ?? 'asc');

        //Execute Output
        return $this->executeQuery($query, $filters);

    }
}
