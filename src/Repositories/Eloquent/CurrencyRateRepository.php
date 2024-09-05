<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\CurrencyRateRepository as InterfacesCurrencyRateRepository;
use Fintech\Business\Models\CurrencyRate;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
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

        $query->leftJoin('services', 'currency_rates.service_id', '=', 'services.id');
        $query->leftJoin('service_types', 'services.service_type_id', '=', 'service_types.id');

        //Searching
        if (!empty($filters['search'])) {
            $query->where(function (Builder $query) use ($filters) {
                return $query->where('currency_rates.'.$this->model->getKeyName(), 'like', "%{$filters['search']}%")
                    ->orWhere('name', 'like', "%{$filters['search']}%")
                    ->orWhere('currency_rate_data', 'like', "%{$filters['search']}%")
                    ->orWhere('service_name', 'like', "%{$filters['search']}%")
                    ->orWhere('service_slug', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['id_not_in'])) {
            $query->whereNotIn($this->model->getKeyName(), (array)$filters['id_not_in']);
        }

        if (!empty($filters['id_in'])) {
            $query->whereIn($this->model->getKeyName(), (array)$filters['id_in']);
        }

        if (!empty($filters['source_country_id'])) {
            $query->where('currency_rates.source_country_id', '=', $filters['source_country_id']);
        }

        if (!empty($filters['destination_country_id'])) {
            $query->where('currency_rates.destination_country_id', '=', $filters['destination_country_id']);
        }

        if (!empty($filters['service_id'])) {
            $query->where('service_id', '=', $filters['service_id']);
        }

        if (!empty($filters['service_type_id'])) {
            $query->where('service_types.service_type_parent_id', '=', $filters['service_type_id']);
        }

        //Display Trashed
        if (isset($filters['trashed']) && $filters['trashed'] === true) {
            $query->onlyTrashed();
        }

        $query->select(['currency_rates.*']);
        //Handle Sorting
        $query->orderBy($filters['sort'] ?? $this->model->getKeyName(), $filters['dir'] ?? 'asc');

        //Execute Output
        return $this->executeQuery($query, $filters);

    }
}
