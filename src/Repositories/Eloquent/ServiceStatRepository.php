<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\ServiceStatRepository as InterfacesServiceStatRepository;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Class ServiceStatRepository
 */
class ServiceStatRepository extends EloquentRepository implements InterfacesServiceStatRepository
{
    public function __construct()
    {
        $model = app(config('fintech.business.service_stat_model', \Fintech\Business\Models\ServiceStat::class));

        if (! $model instanceof Model) {
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
                $query->where(get_table('business.service_stat').'.service_slug', 'like', "%{$filters['search']}%");
            }
        }

        if (isset($filters['role_id']) && $filters['role_id']) {
            $query->where(get_table('business.service_stat').'.role_id', '=', $filters['role_id']);
        }
        if (isset($filters['service_id']) && $filters['service_id']) {
            $query->where(get_table('business.service_stat').'.service_id', '=', $filters['service_id']);
        }
        if (isset($filters['source_country_id']) && $filters['source_country_id']) {
            $query->where(get_table('business.service_stat').'.source_country_id', '=', $filters['source_country_id']);
        }
        if (isset($filters['destination_country_id']) && $filters['destination_country_id']) {
            $query->where(get_table('business.service_stat').'.destination_country_id', '=', $filters['destination_country_id']);
        }
        if (isset($filters['service_vendor_id']) && $filters['service_vendor_id']) {
            $query->where(get_table('business.service_stat').'.service_vendor_id', '=', $filters['service_vendor_id']);
        }
        if (isset($filters['service_slug']) && $filters['service_slug']) {
            $query->where(get_table('business.service_stat').'.service_slug', '=', $filters['service_slug']);
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
