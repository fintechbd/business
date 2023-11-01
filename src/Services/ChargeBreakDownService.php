<?php

namespace Fintech\Business\Services;


use Fintech\Business\Interfaces\ChargeBreakDownRepository;

/**
 * Class ChargeBreakDownService
 * @package Fintech\Business\Services
 *
 */
class ChargeBreakDownService
{
    /**
     * ChargeBreakDownService constructor.
     * @param ChargeBreakDownRepository $chargeBreakDownRepository
     */
    public function __construct(ChargeBreakDownRepository $chargeBreakDownRepository) {
        $this->chargeBreakDownRepository = $chargeBreakDownRepository;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->chargeBreakDownRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->chargeBreakDownRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->chargeBreakDownRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->chargeBreakDownRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->chargeBreakDownRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->chargeBreakDownRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->permissionRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->permissionRepository->create($filters);
    }
}
