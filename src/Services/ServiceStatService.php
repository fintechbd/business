<?php

namespace Fintech\Business\Services;

use Exception;
use Fintech\Business\Facades\Business;
use Fintech\Business\Interfaces\ServiceStatRepository;
use Fintech\Core\Abstracts\BaseModel;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Class ServiceStatService
 */
class ServiceStatService
{
    /**
     * ServiceStatService constructor.
     */
    public function __construct(private readonly ServiceStatRepository $serviceStatRepository) {}

    public function find($id, bool $onlyTrashed = false): ?BaseModel
    {
        return $this->serviceStatRepository->find($id, $onlyTrashed);
    }

    public function update($id, array $inputs = []): ?BaseModel
    {
        return $this->serviceStatRepository->update($id, $inputs);
    }

    public function destroy($id): mixed
    {
        return $this->serviceStatRepository->delete($id);
    }

    public function restore($id): mixed
    {
        return $this->serviceStatRepository->restore($id);
    }

    public function export(array $filters): Paginator|Collection
    {
        return $this->serviceStatRepository->list($filters);
    }

    public function list(array $filters = []): Paginator|Collection
    {
        return $this->serviceStatRepository->list($filters);

    }

    public function import(array $filters): ?BaseModel
    {
        return $this->serviceStatRepository->create($filters);
    }

    public function create(array $inputs = []): ?BaseModel
    {
        return $this->serviceStatRepository->create($inputs);
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

    /**
     * @return array{charge: mixed, discount: mixed, commission: mixed, charge_refund: mixed|null, discount_refund: mixed|null, commission_refund: mixed|null, charge_break_down_id: mixed, service_stat_id: mixed|null}
     *
     * @throws Exception
     */
    public function serviceStateData($data): array
    {
        $data->role_id = $data->user->roles[0]->getKey();
        $serviceStateData['role_id'] = $data->role_id;
        $serviceStateData['service_id'] = $data->service_id;
        $serviceStateData['source_country_id'] = $data->source_country_id;
        $serviceStateData['destination_country_id'] = $data->destination_country_id;
        $serviceStateData['amount'] = $data->amount;
        $serviceStateData['enable'] = true;
        $serviceStates = Business::serviceStat()->list($serviceStateData)->first();
        if (! $serviceStates) {
            throw new Exception('Service State Data not found');
        }
        $serviceState = $serviceStates->toArray();
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
            'charge_refund' => $serviceState['charge_refund'] ?? null,
            'discount_refund' => $serviceState['discount_refund'] ?? null,
            'commission_refund' => $serviceState['commission_refund'] ?? null,
            'charge_break_down_id' => $serviceStateJsonData['charge_break_down_id'] ?? null,
            'service_stat_id' => $serviceStateJsonData['service_stat_id'] ?? null,
        ];
    }

    /**
     * @return array{0: array|float, charge: mixed|null, charge_amount: float|int, discount: mixed|null, discount_amount: float|int, commission: mixed|null, commission_amount: float|int, total_amount: mixed}
     */
    public function cost(array $inputs): array
    {
        $currencyRateParams = [
            'service_id' => $inputs['service_id'],
            'source_country_id' => $inputs['source_country_id'],
            'destination_country_id' => $inputs['destination_country_id'],
            'amount' => $inputs['amount'],
            'reverse' => $inputs['reverse'],
        ];

        $exchangeRate = Business::currencyRate()->convert($currencyRateParams);
        if (! $exchangeRate) {
            //throw (new ModelNotFoundException())->setModel(config('fintech.business.service_stat_model', ServiceStat::class), $inputs);
            throw new ModelNotFoundException("Currency Convert Rate doesn't exists");
        }
        $serviceStat = $this->list([
            'role_id' => $inputs['role_id'],
            'service_id' => $inputs['service_id'],
            'source_country_id' => $inputs['source_country_id'],
            'destination_country_id' => $inputs['destination_country_id'],
        ])->first();

        if (! $serviceStat) {
            //throw (new ModelNotFoundException())->setModel(config('fintech.business.service_stat_model', ServiceStat::class), $inputs);
            throw new ModelNotFoundException("Service State doesn't exists");
        }

        $serviceStatData = $serviceStat->service_stat_data[0];

        logger('service_stat_data', $serviceStatData);

        $serviceCost = [
            ...$exchangeRate,
            'charge' => $serviceStatData['charge'] ?? null,
            'charge_amount' => calculate_flat_percent($inputs['amount'], $serviceStatData['charge']),
            'discount' => $serviceStatData['discount'] ?? null,
            'discount_amount' => calculate_flat_percent($inputs['amount'], $serviceStatData['discount']),
            'commission' => $serviceStatData['commission'] ?? null,
            'commission_amount' => calculate_flat_percent($inputs['amount'], $serviceStatData['commission']),
        ];

        $serviceCost['total_amount'] = ($inputs['amount'] + $serviceCost['charge_amount']) - $serviceCost['discount_amount'];

        return $serviceCost;
    }
}
