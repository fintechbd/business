<?php

namespace Fintech\Business\Services;

use Fintech\Business\Facades\Business;
use Fintech\Business\Interfaces\ServiceStateRepository;

/**
 * Class ServiceStateService
 */
class ServiceStateService
{
    /**
     * ServiceStateService constructor.
     */
    public function __construct(ServiceStateRepository $serviceStateRepository)
    {
        $this->serviceStateRepository = $serviceStateRepository;
    }

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->serviceStateRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->serviceStateRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->serviceStateRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->serviceStateRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->serviceStateRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->serviceStateRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->serviceStateRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->serviceStateRepository->create($filters);
    }

    public function customStore(array $data): array
    {
        $inputs = $data;
        $serviceStates = [];
        if (is_array($data['role_id'])) {
            foreach ($data['role_id'] as $role) {
                $inputs['role_id'] = $role;
                if (is_array($data['source_country_id'])) {
                    foreach ($data['source_country_id'] as $source_country) {
                        $inputs['source_country_id'] = $source_country;
                        if (is_array($data['destination_country_id'])) {
                            foreach ($data['destination_country_id'] as $destination_country) {
                                $inputs['destination_country_id'] = $destination_country;
                                $serviceState = Business::serviceState()->create($inputs);
                                $serviceStates[] = $serviceState->id;
                                //$serviceStates[] = $inputs;
                            }
                        }
                    }
                }
            }
        }

        return $serviceStates;

    }
}
