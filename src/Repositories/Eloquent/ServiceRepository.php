<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\ServiceRepository as InterfacesServiceRepository;
use Fintech\Business\Models\Service;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ServiceRepository
 */
class ServiceRepository extends EloquentRepository implements InterfacesServiceRepository
{
    public function __construct()
    {
        parent::__construct(config('fintech.business.service_model', Service::class));
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
        if (isset($filters['search']) && ! empty($filters['search'])) {
            if (is_numeric($filters['search'])) {
                $query->where($this->model->getKeyName(), 'like', "%{$filters['search']}%");
            } else {
                $query->where($modelTable.'.service_name', 'like', "%{$filters['search']}%");
            }
        }

        if (isset($filters['service_id']) && $filters['service_id']) {
            $query->where($modelTable.'.id', '=', $filters['service_id']);
        }

        if (isset($filters['service_slug']) && $filters['service_slug']) {
            $query->where($modelTable.'.service_slug', '=', $filters['service_slug']);
        }

        if (isset($filters['service_delay']) && $filters['service_delay']) {
            $query->where($modelTable.'.service_delay', '=', $filters['service_delay']);
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
