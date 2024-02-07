<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\ServiceFieldRepository as InterfacesServiceFieldRepository;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Class ServiceFieldRepository
 */
class ServiceFieldRepository extends EloquentRepository implements InterfacesServiceFieldRepository
{
    public function __construct()
    {
        $model = app(config('fintech.business.service_field_model', \Fintech\Business\Models\ServiceField::class));

        if (!$model instanceof Model) {
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
        if (!empty($filters['search'])) {
            if (is_numeric($filters['search'])) {
                $query->where($this->model->getKeyName(), 'like', "%{$filters['search']}%");
            } else {
                $query->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('label', 'like', "%{$filters['search']}%")
                    ->orWhere('type', 'like', "%{$filters['search']}%")
                    ->orWhere('options', 'like', "%{$filters['search']}%")
                    ->orWhere('value', 'like', "%{$filters['search']}%")
                    ->orWhere('hint', 'like', "%{$filters['search']}%")
                    ->orWhere('validation', 'like', "%{$filters['search']}%")
                    ->orWhere('service_field_data', 'like', "%{$filters['search']}%");
            }
        }

        if (!empty($filters['service_id'])) {
            $query->where('service_id', $filters['service_id']);
        }

        //Display Trashed
        if (isset($filters['trashed']) && $filters['trashed'] === true) {
            $query->onlyTrashed();
        }

        //Handle Sorting
        $query->orderBy($filters['sort'] ?? $this->model->getKeyName(), $filters['dir'] ?? 'asc');

        //Execute Output
        return $this->executeQuery($query, $filters);

    }
}
