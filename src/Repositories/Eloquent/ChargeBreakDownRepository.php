<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\ChargeBreakDownRepository as InterfacesChargeBreakDownRepository;
use Fintech\Business\Models\ChargeBreakDown;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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
     */
    public function list(array $filters = [])
    {
        $query = $this->model->newQuery();

        //Searching
        if (isset($filters['search']) && !empty($filters['search'])) {
            if (is_numeric($filters['search'])) {
                $query->where($this->model->getKeyName(), 'like', "%{$filters['search']}%");
            } else {
                $query->where('service_slug', 'like', "%{$filters['search']}%");
            }
        }

        if (isset($filters['amount']) && !empty($filters['amount'])) {
            $query->whereBetween(DB::raw($filters['amount']), [DB::raw(get_table('business.charge_break_down') . '.charge_break_down_lower'), DB::raw(get_table('business.charge_break_down') . '.charge_break_down_higher')]);
        }

        if (isset($filters['service_stat_id']) && !empty($filters['service_stat_id'])) {
            $query->where('service_stat_id', $filters['service_stat_id']);
        }

        if (isset($filters['enabled']) && !empty($filters['enabled'])) {
            $query->where('enabled', $filters['enabled']);
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
