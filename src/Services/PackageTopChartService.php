<?php

namespace Fintech\Business\Services;


use Fintech\Business\Interfaces\PackageTopChartRepository;

/**
 * Class PackageTopChartService
 * @package Fintech\Business\Services
 *
 */
class PackageTopChartService
{
    /**
     * PackageTopChartService constructor.
     * @param PackageTopChartRepository $packageTopChartRepository
     */
    public function __construct(PackageTopChartRepository $packageTopChartRepository) {
        $this->packageTopChartRepository = $packageTopChartRepository;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->packageTopChartRepository->list($filters);

    }

    public function create(array $inputs = [])
    {
        return $this->packageTopChartRepository->create($inputs);
    }

    public function find($id, $onlyTrashed = false)
    {
        return $this->packageTopChartRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = [])
    {
        return $this->packageTopChartRepository->update($id, $inputs);
    }

    public function destroy($id)
    {
        return $this->packageTopChartRepository->delete($id);
    }

    public function restore($id)
    {
        return $this->packageTopChartRepository->restore($id);
    }

    public function export(array $filters)
    {
        return $this->packageTopChartRepository->list($filters);
    }

    public function import(array $filters)
    {
        return $this->packageTopChartRepository->create($filters);
    }
}
