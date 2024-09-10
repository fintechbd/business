<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\ServicePackageRepository as InterfacesServicePackageRepository;
use Fintech\Business\Models\ServicePackage;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ServicePackageRepository
 */
class ServicePackageRepository extends EloquentRepository implements InterfacesServicePackageRepository
{
    public function __construct()
    {
        parent::__construct(config('fintech.business.service_package_model', ServicePackage::class));
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

        if (! empty($filters['id_not_in'])) {
            $query->whereNotIn($this->model->getKeyName(), (array) $filters['id_not_in']);
        }

        if (! empty($filters['type'])) {
            $query->where('type', 'like', "%{$filters['type']}%");
        }

        if (isset($filters['country_id'])) {
            $query->where('country_id', '=', $filters['country_id']);
        }

        if (isset($filters['service_id'])) {
            $query->where('service_id', '=', $filters['service_id']);
        }

        if (isset($filters['connection_type'])) {
            $query->where('service_package_data->connection_type', '=', $filters['connection_type']);
        }

        if (isset($filters['enabled'])) {
            $query->where('enabled', '=', $filters['enabled']);
        }

        if (isset($filters['amount'])) {
            $query->where('amount', '=', $filters['amount']);
        }

        if (isset($filters['near_amount'])) {
            $query->where('amount', '>=', $filters['near_amount']);
        }

        if (isset($filters['limit'])) {
            $query->limit($filters['limit']);
        }

        //Display Trashed
        if (isset($filters['trashed']) && ! empty($filters['trashed'])) {
            $query->onlyTrashed();
        }

        //Handle Sorting
        $query->orderBy($filters['sort'] ?? $this->model->getKeyName(), $filters['dir'] ?? 'asc');

        //Execute Output
        return $this->executeQuery($query, $filters);

    }
}
