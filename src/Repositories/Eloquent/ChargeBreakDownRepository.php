<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Core\Repositories\EloquentRepository;
use Fintech\Business\Interfaces\ChargeBreakDownRepository as InterfacesChargeBreakDownRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Class ChargeBreakDownRepository
 * @package Fintech\Business\Repositories\Eloquent
 */
class ChargeBreakDownRepository extends EloquentRepository implements InterfacesChargeBreakDownRepository
{
    public function __construct()
    {
       $model = app(config('fintech.business.charge_break_down_model', \Fintech\Business\Models\ChargeBreakDown::class));

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
        if (isset($filters['search']) && ! empty($filters['search'])) {
            if (is_numeric($filters['search'])) {
                $query->where($this->model->getKeyName(), 'like', "%{$filters['search']}%");
            } else {
                $query->where('name', 'like', "%{$filters['search']}%");
            }
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
