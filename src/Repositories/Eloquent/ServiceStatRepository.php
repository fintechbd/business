<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\ServiceStatRepository as InterfacesServiceStatRepository;
use Fintech\Business\Models\ServiceStat;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ServiceStatRepository
 */
class ServiceStatRepository extends EloquentRepository implements InterfacesServiceStatRepository
{
    public function __construct()
    {
        parent::__construct(config('fintech.business.service_stat_model', ServiceStat::class));
    }

    /**
     * return a list or pagination of items from
     * filtered options
     */
    public function list(array $filters = []): Paginator|Collection
    {
        $query = $this->model->newQuery();
        $modelTable = $this->model->getTable();

        //Searching
        if (!empty($filters['search'])) {
            $query->where($this->model->getKeyName(), 'like', "%{$filters['search']}%")
                ->orWhere('service_slug', 'like', "%{$filters['search']}%")
                ->orWhere('service_stat_data', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['id_not_in'])) {
            $query->whereNotIn($this->model->getKeyName(), (array)$filters['id_not_in']);
        }

        if (!empty($filters['id_in'])) {
            $query->whereIn($this->model->getKeyName(), (array)$filters['id_in']);
        }

        if (isset($filters['role_id']) && $filters['role_id']) {
            $query->where($modelTable . '.role_id', '=', $filters['role_id']);
        }
        if (isset($filters['service_id']) && $filters['service_id']) {
            $query->where($modelTable . '.service_id', '=', $filters['service_id']);
        }
        if (isset($filters['source_country_id']) && $filters['source_country_id']) {
            $query->where($modelTable . '.source_country_id', '=', $filters['source_country_id']);
        }
        if (isset($filters['destination_country_id']) && $filters['destination_country_id']) {
            $query->where($modelTable . '.destination_country_id', '=', $filters['destination_country_id']);
        }
        if (isset($filters['service_vendor_id']) && $filters['service_vendor_id']) {
            $query->where($modelTable . '.service_vendor_id', '=', $filters['service_vendor_id']);
        }
        if (isset($filters['service_slug']) && $filters['service_slug']) {
            $query->where($modelTable . '.service_slug', '=', $filters['service_slug']);
        }

        //Display Trashed
        if (isset($filters['trashed']) && !empty($filters['trashed'])) {
            $query->onlyTrashed();
        }

        //Handle Sorting
        $query->orderBy($filters['sort'] ?? $this->model->getKeyName(), $filters['dir'] ?? 'asc');

        //Execute Output
        return $this->executeQuery($query, $filters);

    }
}
