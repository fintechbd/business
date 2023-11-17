<?php

namespace Fintech\Business\Services;

use Fintech\Business\Facades\Business;
use Fintech\Business\Interfaces\ServiceStatRepository;

/**
 * Class ServiceStatService
 */
class ServiceStatService
{
    /**
     * ServiceStatService constructor.
     */
    public function __construct(private readonly ServiceStatRepository $serviceStatRepository)
    {
    }

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->serviceStatRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->serviceStatRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->serviceStatRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->serviceStatRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->serviceStatRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->serviceStatRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->serviceStatRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->serviceStatRepository->create($filters);
    }

    public function customStore(array $data): array
    {
        $inputs = $data;
        $serviceStats = [];
        if (is_array($data['role_id'])) {
            foreach ($data['role_id'] as $role) {
                $inputs['role_id'] = $role;
                if (is_array($data['source_country_id'])) {
                    foreach ($data['source_country_id'] as $source_country) {
                        $inputs['source_country_id'] = $source_country;
                        if (is_array($data['destination_country_id'])) {
                            foreach ($data['destination_country_id'] as $destination_country) {
                                $inputs['destination_country_id'] = $destination_country;
                                $serviceStat = Business::serviceStat()->create($inputs);
                                $serviceStats[] = $serviceStat->getKey();
                                //$serviceStats[] = $inputs;
                            }
                        }
                    }
                }
            }
        }

        return $serviceStats;

    }
}
