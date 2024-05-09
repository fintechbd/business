<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\CurrencyRateRepository as InterfacesCurrencyRateRepository;
use Fintech\Business\Models\CurrencyRate;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CurrencyRateRepository
 */
class CurrencyRateRepository extends EloquentRepository implements InterfacesCurrencyRateRepository
{
    public function __construct()
    {
        parent::__construct(config('fintech.business.currency_rate_model', CurrencyRate::class));
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
                $query->orWhere('currency_rate_data', 'like', "%{$filters['search']}%");
            }
        }

        if (! empty($filters['source_country_id'])) {
            $query->where('source_country_id', '=', $filters['source_country_id']);
        }

        if (! empty($filters['destination_country_id'])) {
            $query->where('destination_country_id', '=', $filters['destination_country_id']);
        }

        if (! empty($filters['destination_country_id'])) {
            $query->where('destination_country_id', '=', $filters['destination_country_id']);
        }

        if (! empty($filters['service_id'])) {
            $query->where('service_id', '=', $filters['service_id']);
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
