<?php

namespace Fintech\Business\Services;

use Exception;
use Fintech\Business\Exceptions\BusinessException;
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
        $serviceState = $serviceState['service_stat_data'];
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
     *
     * @throws BusinessException
     */
    public function cost(array $inputs): array
    {
        if (! isset($inputs['reverse'])) {
            $inputs['reverse'] = false;
        } else {
            $inputs['reverse'] = ! in_array($inputs['reverse'], ['', '0', 0, 'false', false], true);
        }

        if (! isset($inputs['reload'])) {
            $inputs['reload'] = false;
        } else {
            $inputs['reload'] = ! in_array($inputs['reload'], ['', '0', 0, 'false', false], true);
        }

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

        $service = Business::service()->find($inputs['service_id']);

        $serviceStat = $this->list([
            'role_id' => $inputs['role_id'],
            'service_id' => $service->getKey() ?? $inputs['service_id'],
            'service_vendor_id' => $service->service_vendor_id ?? config('fintech.business.default_vendor', 1),
            'source_country_id' => $inputs['source_country_id'],
            'destination_country_id' => $inputs['destination_country_id'],
        ])->first();

        if (! $serviceStat) {
            //throw (new ModelNotFoundException())->setModel(config('fintech.business.service_stat_model', ServiceStat::class), $inputs);
            throw new ModelNotFoundException("Service Stat doesn't exists");
        }

        $serviceStatData = $serviceStat->service_stat_data;

        $serviceCost = $exchangeRate;

        $baseCurrency = ($inputs['reverse']) ? $serviceCost['output'] : $serviceCost['input'];

        $baseAmount = ($inputs['reverse']) ? $serviceCost['converted'] : $inputs['amount'];

        $serviceCost['base_currency'] = $baseCurrency;

        if (isset($serviceStatData['lower_limit']) && is_numeric($serviceStatData['lower_limit'])) {
            if ($baseAmount < floatval($serviceStatData['lower_limit'])) {
                throw new BusinessException(__('business::messages.service_stat.below_lower_limit'));
            }
        }

        if (isset($serviceStatData['higher_limit']) && is_numeric($serviceStatData['higher_limit'])) {
            if ($baseAmount > floatval($serviceStatData['higher_limit'])) {
                throw new BusinessException(__('business::messages.service_stat.upper_limit_exceed'));
            }
        }

        if (isset($serviceStatData['local_currency_lower_limit']) && is_numeric($serviceStatData['local_currency_lower_limit'])) {
            if ($baseAmount < floatval($serviceStatData['local_currency_lower_limit'])) {
                throw new BusinessException(__('business::messages.service_stat.local_currency_below_lower_limit'));
            }
        }

        if (isset($serviceStatData['local_currency_higher_limit']) && is_numeric($serviceStatData['local_currency_higher_limit'])) {
            if ($baseAmount > floatval($serviceStatData['local_currency_higher_limit'])) {
                throw new BusinessException(__('business::messages.service_stat.local_currency_upper_limit_exceed'));
            }
        }

        $chargeBreakDown = Business::chargeBreakDown()->list([
            'enabled' => true,
            'service_id' => $inputs['service_id'],
            'service_stat_id' => $serviceStat->getKey(),
            'amount' => $baseAmount,
        ])->first();

        $serviceCost['charge_break_down_data'] = [
            'id' => null,
            'lower_limit' => $serviceStatData['lower_limit'] ?? 0,
            'higher_limit' => $serviceStatData['higher_limit'] ?? 0,
        ];

        if ($chargeBreakDown) {
            $serviceStatData['charge'] = $chargeBreakDown->charge;
            $serviceStatData['discount'] = $chargeBreakDown->discount;
            $serviceStatData['commission'] = $chargeBreakDown->commission;
            $chargeBreakDown->setVisible(['id', 'lower_limit', 'higher_limit']);
            $serviceCost['charge_break_down_data'] = $chargeBreakDown->toArray();
        }

        $serviceCost['charge'] = $serviceStatData['charge'] ?? null;
        $serviceCost['charge_amount'] = calculate_flat_percent($baseAmount, $serviceStatData['charge']);

        $serviceCost['discount'] = $serviceStatData['discount'] ?? null;
        $serviceCost['discount_amount'] = calculate_flat_percent($baseAmount, $serviceStatData['discount']);

        $serviceCost['commission'] = $serviceStatData['commission'] ?? null;
        $serviceCost['commission_amount'] = calculate_flat_percent($baseAmount, $serviceStatData['commission']);

        if ($inputs['reload']) {
            $baseAmount -= $serviceCost['charge_amount'];
            $baseAmount += $serviceCost['discount_amount'];
            $baseAmount += $serviceCost['commission_amount'];
        } else {
            $baseAmount += $serviceCost['charge_amount'];
            $baseAmount -= $serviceCost['discount_amount'];
            $baseAmount -= $serviceCost['commission_amount'];
        }

        $serviceCost['total_amount'] = $baseAmount;

        return $serviceCost;
    }
}
