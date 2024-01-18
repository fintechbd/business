<?php

namespace Fintech\Business\Services;


use Fintech\Business\Interfaces\ServiceFieldRepository;

/**
 * Class ServiceFieldService
 * @package Fintech\Business\Services
 *
 */
class ServiceFieldService
{
    /**
     * ServiceFieldService constructor.
     * @param ServiceFieldRepository $serviceFieldRepository
     */
    public function __construct(ServiceFieldRepository $serviceFieldRepository) {
        $this->serviceFieldRepository = $serviceFieldRepository;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->serviceFieldRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->serviceFieldRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->serviceFieldRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->serviceFieldRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->serviceFieldRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->serviceFieldRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->serviceFieldRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->serviceFieldRepository->create($filters);
    }
}
