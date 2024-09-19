<?php

namespace Fintech\Business\Services;

use Fintech\Business\Facades\Business;
use Fintech\Business\Interfaces\ServiceTypeRepository;
use Fintech\Core\Abstracts\BaseModel;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Class ServiceTypeService
 */
class ServiceTypeService
{
    use \Fintech\Core\Traits\HasFindWhereSearch;

    /**
     * ServiceTypeService constructor.
     */
    public function __construct(private readonly ServiceTypeRepository $serviceTypeRepository) {}

    public function find($id, bool $onlyTrashed = false): ?BaseModel
    {
        return $this->serviceTypeRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = []): ?BaseModel
    {
        return $this->serviceTypeRepository->update($id, $inputs);
    }

    public function destroy($id): mixed
    {
        return $this->serviceTypeRepository->delete($id);
    }

    public function restore($id): mixed
    {
        return $this->serviceTypeRepository->restore($id);
    }

    public function export(array $filters): Paginator|Collection
    {
        return $this->serviceTypeRepository->list($filters);
    }

    public function list(array $filters = []): Collection|Paginator
    {
        if (isset($filters['destination_country_id']) && ! is_array($filters['destination_country_id'])) {
            $filters['destination_country_id'] = (array) $filters['destination_country_id'];
        }

        return $this->serviceTypeRepository->list($filters);

    }

    /**
     * return a list of service types that will be displayed on frontend
     * or in mobile application.
     */
    public function available(array $filters = []): Collection
    {
        $input['service_type_enabled'] = true;
        $input['sort'] = $filters['sort'] ?? 'service_types.id';
        $input['dir'] = $filters['dir'] ?? 'asc';
        //        $input['destination_country_id'] = [$filters['destination_country_id'], $filters['source_country_id']];
        $input['destination_country_id'] = [$filters['destination_country_id']];
        $input['paginate'] = false;

        $input = array_merge($filters, $input);

        $serviceTypes = $this->list($input);

        $serviceTypeCollection = collect();

        foreach ($serviceTypes as $serviceType) {

            if ($serviceType->service_type_is_parent == 'no') {
                $inputNo = $input;
                $inputNo['service_join_active'] = true;
                $inputNo['service_type_id'] = $serviceType->id;
                $inputNo['service_enabled'] = true;
                $inputNo['service_vendor_enabled'] = true;
                $inputNo['service_stat_enabled'] = true;

                $fullServiceTypes = $this->list($inputNo);
                if ($fullServiceTypes->isNotEmpty()) {
                    foreach ($fullServiceTypes as $fullServiceType) {
                        $fullServiceType['service_stat_data'] = $fullServiceType['service_stat_data'] ?? [];
                        $fullServiceType['service_data'] = $fullServiceType['service_data'] ?? [];
                        $fullServiceType->logo_svg = Business::service()->find($fullServiceType->service_id)?->getFirstMediaUrl('logo_svg') ?? null;
                        $fullServiceType->logo_png = Business::service()->find($fullServiceType->service_id)?->getFirstMediaUrl('logo_png') ?? null;
                        if (isset($fullServiceType->media)) {
                            unset($fullServiceType->media);
                        }
                        $serviceTypeCollection->push($fullServiceType);
                    }
                }
            } elseif ($serviceType['service_type_is_parent'] == 'yes') {
                $inputYes = $input;
                $collectID = [];
                $findAllChildServiceType = $this->find($serviceType->getKey());
                $arrayFindData[$serviceType->getKey()] = $findAllChildServiceType->allChildList ?? [];
                foreach ($arrayFindData[$serviceType->getKey()] as $allChildAccounts) {
                    $collectID[$serviceType->getKey()][] = $allChildAccounts['id'];
                }

                $inputYes['service_type_id_array'] = $collectID[$serviceType->getKey()] ?? [];
                //TODO may be need to work future
                $inputYes['service_type_parent_id'] = $serviceType->getKey();
                $inputYes['service_type_parent_id_is_null'] = false;
                $inputYes['service_type_id'] = false;
                $findServiceType = $this->list($inputYes)->count();
                if ($findServiceType > 0) {
                    $serviceType->logo_svg = $serviceType?->getFirstMediaUrl('logo_svg') ?? null;
                    $serviceType->logo_png = $serviceType?->getFirstMediaUrl('logo_png') ?? null;
                    if (isset($serviceType->media)) {
                        unset($serviceType->media);
                    }
                    $serviceTypeCollection->push($serviceType);
                }
            } else {
                if (isset($serviceType->media)) {
                    unset($serviceType->media);
                }
                $serviceTypeCollection->push($serviceType);
            }
        }

        return $serviceTypeCollection;
    }

    public function import(array $filters): ?BaseModel
    {
        return $this->serviceTypeRepository->create($filters);
    }

    public function create(array $inputs = []): ?BaseModel
    {
        return $this->serviceTypeRepository->create($inputs);
    }
}
