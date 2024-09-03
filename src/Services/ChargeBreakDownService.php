<?php

namespace Fintech\Business\Services;

use Fintech\Business\Interfaces\ChargeBreakDownRepository;

/**
 * Class ChargeBreakDownService
 */
class ChargeBreakDownService
{
    /**
     * ChargeBreakDownService constructor.
     */
    public function __construct(private readonly ChargeBreakDownRepository $chargeBreakDownRepository)
    {
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
        return $this->chargeBreakDownRepository->list($filters);
    }

    /**
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->chargeBreakDownRepository->list($filters);

    }

    /**
     * Check if this slot is available
     */
    public function available($service_stat_id, $lower, $higher = null): bool
    {
        $slot = $this->list([
            'service_stat_id' => $service_stat_id,
            'enabled' => true,
            'paginate' => false,
            'available_slot' => 'yes'])->first()->toArray();

        if ($slot['lower_limit'] == null && $slot['higher_limit'] == null) {
            return true;
        }

        if ($lower >= $slot['lower_limit'] && $lower <= $slot['higher_limit']) {
            return false;
        }

        if ($higher != null) {
            if ($higher >= $slot['lower_limit'] && $higher <= $slot['higher_limit']) {
                return true;
            }

            if ($lower <= $slot['lower_limit'] && $higher >= $slot['higher_limit']) {
                return true;
            }
        }
        return true;
    }

    public function import(array $filters)
    {
        return $this->chargeBreakDownRepository->create($filters);
    }

    public function create(array $inputs = [])
    {
        return $this->chargeBreakDownRepository->create($inputs);
    }
}
