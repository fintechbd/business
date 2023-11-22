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

    public function serviceStateData($data): array
    {
        $data->role_id = $data->user->roles[0]->getKey();
        $serviceStateData['role_id'] = $data->role_id;
        $serviceStateData['service_id'] = $data->service_id;
        $serviceStateData['source_country_id'] = $data->source_country_id;
        $serviceStateData['destination_country_id'] = $data->destination_country_id;
        $serviceStateData['amount'] = $data->amount;
        $serviceStateData['enable'] = true;
        $serviceState = Business::serviceStat()->list($serviceStateData)->first()->toArray();

        $serviceStateData['service_stat_id'] = $serviceState['id'];
        $charge_break_down = Business::chargeBreakDown()->list($serviceStateData)->first();
        $serviceState = $serviceState['service_stat_data'][0];
        if ($charge_break_down) {
            $serviceStateJsonData['charge'] = $charge_break_down->charge_break_down_charge;
            $serviceStateJsonData['discount'] = $charge_break_down->charge_break_down_discount;
            $serviceStateJsonData['commission'] = $charge_break_down->charge_break_down_commission;
            $serviceStateJsonData['charge_break_down_id'] = $charge_break_down->getKey();
        } else {
            $serviceStateJsonData['charge'] = $serviceState['charge'];
            $serviceStateJsonData['discount'] = $serviceState['discount'];
            $serviceStateJsonData['commission'] = $serviceState['commission'];
            $serviceStateJsonData['service_stat_id'] = $serviceStateData['service_stat_id'];
        }

        return [
            'charge' => $serviceStateJsonData['charge'] ?? 0,
            'discount' => $serviceStateJsonData['discount'] ?? 0,
            'commission' => $serviceStateJsonData['commission'] ?? 0,
            'charge_refund' => $serviceState['charge_refund'] ?? 0,
            'discount_refund' => $serviceState['discount_refund'] ?? 0,
            'commission_refund' => $serviceState['commission_refund'] ?? 0,
            'charge_break_down_id' => $serviceStateJsonData['charge_break_down_id'] ?? null,
            'service_stat_id' => $serviceStateJsonData['service_stat_id'] ?? null,
        ];
    }
}
