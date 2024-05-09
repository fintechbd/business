<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\ServiceTypeRepository as InterfacesServiceTypeRepository;
use Fintech\Business\Models\ServiceType;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

/**
 * Class ServiceTypeRepository
 */
class ServiceTypeRepository extends EloquentRepository implements InterfacesServiceTypeRepository
{
    public function __construct()
    {
        parent::__construct(config('fintech.business.service_type_model', ServiceType::class));
    }

    /**
     * return a list or pagination of items from
     * filtered options
     *
     * @throws BindingResolutionException
     */
    public function list(array $filters = []): Paginator|Collection
    {
        $query = $this->model->newQuery();

        if (isset($filters['service_join_active']) && $filters['service_join_active'] == true) {
            $query->leftJoin('services', 'service_types.id', '=', 'services.service_type_id');

            $query->leftJoin('service_vendors', 'service_vendors.id', '=', 'services.service_vendor_id');

            $query->leftjoin('service_stats', function (JoinClause $join) {
                $join->on('service_stats.service_id', '=', 'services.id')
                    ->on('service_stats.service_vendor_id', '=', 'services.service_vendor_id');
            });

            $query->join('role_service', function (JoinClause $join) {
                $join->on('service_stats.service_id', '=', 'role_service.service_id');
                $join->on('service_stats.role_id', '=', 'role_service.role_id');
            });

            $query->join('country_service', function ($join) {
                $join->on('service_stats.service_id', '=', 'country_service.service_id');
                $join->on('service_stats.destination_country_id', '=', 'country_service.country_id');
            });
            if (isset($filters['search']) && $filters['search']) {
                $query->where('service_types.service_type_name', 'like', "%{$filters['search']}%");
                $query->orWhere('service_vendors.service_vendor_name', 'like', "%{$filters['search']}%");
            }

            if (isset($filters['destination_country_id']) && $filters['destination_country_id']) {
                $query->where('service_stats.destination_country_id', '=', $filters['destination_country_id']);
            }
            //@TODO show local and targeted destination country services
            //            if (isset($filters['destination_country_id']) && $filters['destination_country_id']) {
            //                $query->where(function ($query) use ($filters) {
            //                    $query->where('service_stats.destination_country_id', '=', $filters['destination_country_id']);
            //                    if (isset($filters['source_country_id']) && $filters['source_country_id']) {
            //                        $query->orWhere('service_stats.destination_country_id', '=', $filters['source_country_id']);
            //                    }
            //                });
            //            }

            if (isset($filters['service_type_id']) && $filters['service_type_id']) {
                $query->where('services.service_type_id', '=', $filters['service_type_id']);
            }

            if (isset($filters['service_id']) && $filters['service_id']) {
                $query->where('services.id', '=', $filters['service_id']);
            }

            if (isset($filters['service_type_id_array']) && $filters['service_type_id_array']) {
                $query->whereIn('services.service_type_id', $filters['service_type_id_array']);
            }

            if (isset($filters['role_id']) && $filters['role_id']) {
                $query->where('service_stats.role_id', '=', $filters['role_id']);
            }

            if (isset($filters['source_country_id']) && $filters['source_country_id']) {
                $query->where('service_stats.source_country_id', '=', $filters['source_country_id']);
            }

            if (isset($filters['service_type_slug']) && $filters['service_type_slug']) {
                $query->where('service_types.service_type_slug', '=', $filters['service_type_slug']);
            }

            //SERVICE STATE DATA
            if (isset($filters['visible_android_app']) && $filters['visible_android_app']) {
                $query->where('services.service_data->visible_android_app', '=', $filters['visible_android_app']);
            }

            if (isset($filters['visible_ios_app']) && $filters['visible_ios_app']) {
                $query->where('services.service_data->visible_ios_app', '=', $filters['visible_ios_app']);
            }

            if (isset($filters['visible_website']) && $filters['visible_website']) {
                $query->where('services.service_data->visible_website', '=', $filters['visible_website']);
            }

            if (isset($filters['account_name']) && $filters['account_name']) {
                $query->where('services.service_data->account_name', '=', $filters['account_name']);
            }

            if (isset($filters['account_number']) && $filters['account_number']) {
                $query->where('services.service_data->account_number', '=', $filters['account_number']);
            }

            if (isset($filters['transactional_currency']) && $filters['transactional_currency']) {
                $query->where('services.service_data->transactional_currency', '=', $filters['transactional_currency']);
            }

            if (isset($filters['beneficiary_type_id']) && $filters['beneficiary_type_id']) {
                $query->where('services.service_data->beneficiary_type_id', '=', $filters['beneficiary_type_id']);
            }

            if (isset($filters['operator_short_code']) && $filters['operator_short_code']) {
                $query->where('services.service_data->operator_short_code', '=', $filters['operator_short_code']);
            }

            //SERVICE STATE DATA
            if (isset($filters['lower_limit']) && $filters['lower_limit']) {
                $query->where('service_stats.service_stat_data->lower_limit', '=', $filters['lower_limit']);
            }

            if (isset($filters['higher_limit']) && $filters['higher_limit']) {
                $query->where('service_stats.service_stat_data->higher_limit', '=', $filters['higher_limit']);
            }

            if (isset($filters['local_currency_higher_limit']) && $filters['local_currency_higher_limit']) {
                $query->where('service_stats.service_stat_data->local_currency_higher_limit', '=', $filters['local_currency_higher_limit']);
            }

            if (isset($filters['charge']) && $filters['charge']) {
                $query->where('service_stats.service_stat_data->charge', '=', $filters['charge']);
            }

            if (isset($filters['discount']) && $filters['discount']) {
                $query->where('service_stats.service_stat_data->discount', '=', $filters['discount']);
            }

            if (isset($filters['commission']) && $filters['commission']) {
                $query->where('service_stats.service_stat_data->commission', '=', $filters['commission']);
            }

            if (isset($filters['cost']) && $filters['cost']) {
                $query->where('service_stats.service_stat_data->cost', '=', $filters['cost']);
            }

            if (isset($filters['charge_refund']) && $filters['charge_refund']) {
                $query->where('service_stats.service_stat_data->charge_refund', '=', $filters['charge_refund']);
            }

            if (isset($filters['discount_refund']) && $filters['discount_refund']) {
                $query->where('service_stats.service_stat_data->discount_refund', '=', $filters['discount_refund']);
            }

            if (isset($filters['commission_refund']) && $filters['commission_refund']) {
                $query->where('service_stats.service_stat_data->commission_refund', '=', $filters['commission_refund']);
            }

            if (isset($filters['service_enabled']) && $filters['service_enabled']) {
                $query->where('services.enabled', '=', $filters['service_enabled']);
            }

            if (isset($filters['service_vendor_enabled']) && $filters['service_vendor_enabled']) {
                $query->where('service_vendors' . '.enabled', '=', $filters['service_vendor_enabled']);
            }

            if (isset($filters['service_stat_enabled']) && $filters['service_stat_enabled']) {
                $query->where('service_stats.enabled', '=', $filters['service_stat_enabled']);
            }

            $select = [
                'service_stats.*',
                'service_vendors' . '.*',
                'services.*',
                DB::raw('service_stats.id as service_stat_id')];
        }

        //Searching
        if (isset($filters['search']) && !empty($filters['search'])) {
            if (is_numeric($filters['search'])) {
                $query->where($this->model->getKeyName(), 'like', "%{$filters['search']}%");
            } else {
                $query->where('service_types.service_type_name', 'like', "%{$filters['search']}%");
                $query->orWhere('service_types.service_type_slug', 'like', "%{$filters['search']}%");
            }
        }

        if (isset($filters['id']) && $filters['id'] > 0) {
            $query->where('service_types.id', '=', $filters['id']);
        }

        if (isset($filters['service_type_id']) && $filters['service_type_id'] > 0) {
            $query->where('service_types.id', '=', $filters['service_type_id']);
        }

        if (isset($filters['service_type_name']) && $filters['service_type_name']) {
            $query->where('service_types.service_type_name', '=', $filters['service_type_name']);
        }

        if (isset($filters['service_type_slug']) && $filters['service_type_slug']) {
            $query->where('service_types.service_type_slug', '=', $filters['service_type_slug']);
        }

        if (isset($filters['service_type_parent_id']) && $filters['service_type_parent_id']) {
            $query->where('service_types.service_type_parent_id', '=', $filters['service_type_parent_id']);
        }

        if (isset($filters['service_type_parent_id_is_null']) && $filters['service_type_parent_id_is_null'] == true) {
            $query->whereNull('service_types.service_type_parent_id');
        }

        if (isset($filters['service_type_is_parent']) && $filters['service_type_is_parent']) {
            $query->where('service_types.service_type_is_parent', '=', $filters['service_type_is_parent']);
        }

        if (isset($filters['service_type_slug']) && $filters['service_type_slug']) {
            $query->where('service_types.service_type_slug', '=', $filters['service_type_slug']);
        }

        if (isset($filters['service_type_is_description']) && $filters['service_type_is_description']) {
            $query->where('service_types.service_type_is_description', '=', $filters['service_type_is_description']);
        }

        if (isset($filters['service_type_enabled']) && $filters['service_type_enabled']) {
            $query->where('service_types.enabled', '=', $filters['service_type_enabled']);
        }

        //Display Trashed
        if (isset($filters['trashed']) && !empty($filters['trashed'])) {
            $query->onlyTrashed();
        }

        //Handle Sorting
        $query->orderBy($filters['sort'] ?? $this->model->getKeyName(), $filters['dir'] ?? 'asc');

        $select[] = 'service_types.*';
        $query->select($select);

        //Execute Output
        return $this->executeQuery($query, $filters);

    }
}
