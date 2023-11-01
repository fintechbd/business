<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ServiceRepository;

/**
 * Class ServiceService
 */
class ServiceService
{
    /**
     * ServiceService constructor.
     */
    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->serviceRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->serviceRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->serviceRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->serviceRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->serviceRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->serviceRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->serviceRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->serviceRepository->create($filters);
    }
}
