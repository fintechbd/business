<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\CurrencyRateRepository as InterfacesCurrencyRateRepository;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Class CurrencyRateRepository
 */
class CurrencyRateRepository extends EloquentRepository implements InterfacesCurrencyRateRepository
{
    public function __construct()
    {
        $model = app(config('fintech.business.currency_rate_model', \Fintech\Business\Models\CurrencyRate::class));

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
                $query->where('name', 'like', "%{$filters['search']}%");
                $query->orWhere('currency_rate_data', 'like', "%{$filters['search']}%");
            }
        }

        if (!empty($filters['source_country_id'])) {
            $query->where('source_country_id', '=', $filters['source_country_id']);
        }

        if (!empty($filters['destination_country_id'])) {
            $query->where('destination_country_id', '=', $filters['destination_country_id']);
        }

        if (!empty($filters['order_detail_parent_id'])) {
            $query->where('order_detail_parent_id', '=', $filters['order_detail_parent_id']);
        }

        if (!empty($filters['sender_receiver_id'])) {
            $query->where('sender_receiver_id', '=', $filters['sender_receiver_id']);
        }

        if (!empty($filters['service_id'])) {
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
