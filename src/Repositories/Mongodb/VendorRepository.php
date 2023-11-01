<?php

namespace Fintech\Business\Repositories\Mongodb;

use Fintech\Core\Repositories\MongodbRepository;
use Fintech\Business\Interfaces\VendorRepository as InterfacesVendorRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use MongoDB\Laravel\Eloquent\Model;
use InvalidArgumentException;

/**
 * Class VendorRepository
 * @package Fintech\Business\Repositories\Mongodb
 */
class VendorRepository extends MongodbRepository implements InterfacesVendorRepository
{
    public function __construct()
    {
       $model = app(config('fintech.business.vendor_model', \Fintech\Business\Models\Vendor::class));

       if (!$model instanceof Model) {
           throw new InvalidArgumentException("Mongodb repository require model class to be `MongoDB\Laravel\Eloquent\Model` instance.");
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
