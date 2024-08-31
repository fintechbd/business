<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\ChargeBreakDownRepository as InterfacesChargeBreakDownRepository;
use Fintech\Business\Models\ChargeBreakDown;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ChargeBreakDownRepository
 */
class ChargeBreakDownRepository extends EloquentRepository implements InterfacesChargeBreakDownRepository
{
    public function __construct()
    {
        parent::__construct(config('fintech.business.charge_break_down_model', ChargeBreakDown::class));
    }

    /**
     * return a list or pagination of items from
     * filtered options
     *
     * @return Paginator|Collection
     *
     * @throws BindingResolutionException
     */
    public function list(array $filters = [])
    {
        $query = $this->model->newQuery();

        //Searching
        if (! empty($filters['search'])) {
            $query->where(function ($query) use ($filters) {
                return $query->where('higher_limit', 'like', "%{$filters['search']}%")
                    ->orWhere('discount', 'like', "%{$filters['search']}%")
                    ->orWhere('charge', 'like', "%{$filters['search']}%")
                    ->orWhere('commission', 'like', "%{$filters['search']}%")
                    ->orWhere('lower_limit', 'like', "%{$filters['search']}%");
            });
        }

        if (! empty($filters['amount'])) {
            $query->where('higher_limit', '>=', $filters['amount'])
                ->where('lower_limit', '<=', $filters['amount']);
        }

        if (! empty($filters['service_stat_id'])) {
            $query->where('service_stat_id', $filters['service_stat_id']);
        }

        if (! empty($filters['service_id'])) {
            $query->where('service_id', $filters['service_id']);
        }

        if (! empty($filters['enabled'])) {
            $query->where('enabled', $filters['enabled']);
        }

        if (! empty($filters['id_not_in'])) {
            $query->whereNotIn($this->model->getKeyName(), (array) $filters['id_not_in']);
        }

        if (! empty($filters['id_in'])) {
            $query->whereIn($this->model->getKeyName(), (array) $filters['id_in']);
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
