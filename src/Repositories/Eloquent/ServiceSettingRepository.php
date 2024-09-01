<?php

namespace Fintech\Business\Repositories\Eloquent;

use Fintech\Business\Interfaces\ServiceSettingRepository as InterfacesServiceSettingRepository;
use Fintech\Business\Models\ServiceSetting;
use Fintech\Core\Repositories\EloquentRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ServiceSettingRepository
 */
class ServiceSettingRepository extends EloquentRepository implements InterfacesServiceSettingRepository
{
    public function __construct()
    {
        parent::__construct(config('fintech.business.service_setting_model', ServiceSetting::class));
    }

    /**
     * return a list or pagination of items from
     * filtered options
     */
    public function list(array $filters = []): Paginator|Collection
    {
        $query = $this->model->newQuery();

        //Searching
        if (! empty($filters['search'])) {
            $query->where($this->model->getKeyName(), 'like', "%{$filters['search']}%")
                ->orWhere('service_setting_type', 'like', "%{$filters['search']}%")
                ->orWhere('service_setting_name', 'like', "%{$filters['search']}%")
                ->orWhere('service_setting_field_name', 'like', "%{$filters['search']}%")
                ->orWhere('service_setting_type_field', 'like', "%{$filters['search']}%")
                ->orWhere('service_setting_feature', 'like', "%{$filters['search']}%");
        }

        if (! empty($filters['id_not_in'])) {
            $query->whereNotIn($this->model->getKeyName(), (array) $filters['id_not_in']);
        }

        if (! empty($filters['id_in'])) {
            $query->whereIn($this->model->getKeyName(), (array) $filters['id_in']);
        }

        if (! empty($filters['service_setting_type'])) {
            $query->where('service_setting_type', '=', $filters['service_setting_type']);
        }

        if (! empty($filters['service_setting_name'])) {
            $query->where('service_setting_name', '=', $filters['service_setting_name']);
        }

        if (! empty($filters['service_setting_field_name'])) {
            $query->where('service_setting_field_name', '=', $filters['service_setting_field_name']);
        }
        if (! empty($filters['service_setting_field_name_not_in'])) {
            $query->whereNotIn('service_setting_field_name', (array)$filters['service_setting_field_name_not_in']);
        }

        if (! empty($filters['service_setting_type_field'])) {
            $query->where('service_setting_type_field', '=', $filters['service_setting_type_field']);
        }

        if (! empty($filters['service_setting_feature'])) {
            $query->where('service_setting_feature', '=', $filters['service_setting_feature']);
        }

        if (isset($filters['enabled']) && is_bool($filters['enabled'])) {
            $query->where('enabled', '=', $filters['enabled']);
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
