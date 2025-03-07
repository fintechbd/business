<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\ServiceFieldRepository as InterfacesServiceFieldRepository;
use Fintech\Business\Models\ServiceField;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ServiceFieldRepository
 */
class ServiceFieldRepository extends EloquentRepository implements InterfacesServiceFieldRepository
{
    public function __construct()
    {
        parent::__construct(config('fintech.business.service_field_model', ServiceField::class));
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

        // Searching
        if (! empty($filters['search'])) {
            $query->leftJoin('services', 'services.id', '=', 'service_fields.service_id');
            $query->where(function ($query) use ($filters) {
                return $query->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('label', 'like', "%{$filters['search']}%")
                    ->orWhere('type', 'like', "%{$filters['search']}%")
                    ->orWhere('options', 'like', "%{$filters['search']}%")
                    ->orWhere('value', 'like', "%{$filters['search']}%")
                    ->orWhere('hint', 'like', "%{$filters['search']}%")
                    ->orWhere('validation', 'like', "%{$filters['search']}%")
                    ->orWhere('services.service_name', 'like', "%{$filters['search']}%")
                    ->orWhere('services.service_data', 'like', "%{$filters['search']}%")
                    ->orWhere('service_field_data', 'like', "%{$filters['search']}%");
            });
        }

        if (! empty($filters['id_not_in'])) {
            $query->whereNotIn($this->model->getKeyName(), (array) $filters['id_not_in']);
        }

        if (! empty($filters['id_in'])) {
            $query->whereIn($this->model->getKeyName(), (array) $filters['id_in']);
        }

        if (! empty($filters['service_id'])) {
            $query->where('service_id', $filters['service_id']);
        }

        // Display Trashed
        if (isset($filters['trashed']) && $filters['trashed'] === true) {
            $query->onlyTrashed();
        }

        $query->select($this->model->getTable().'.*');

        // Handle Sorting
        $query->orderBy($filters['sort'] ?? $this->model->getKeyName(), $filters['dir'] ?? 'asc');

        // Execute Output
        return $this->executeQuery($query, $filters);

    }
}
