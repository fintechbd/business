<?php

namespace Fintech\Business\Services;

use Exception;
use Fintech\Business\Exceptions\BusinessException;
use Fintech\Business\Facades\Business;
use Fintech\Business\Interfaces\ServiceStatRepository;
use Fintech\Core\Abstracts\BaseModel;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class ServiceStatService
 */
class ServiceStatService
{
    use \Fintech\Core\Traits\HasFindWhereSearch;

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
    public function oldServiceStateData($order): array
    {
        $order->role_id = $order->user->roles[0]->getKey();
        $serviceStateData['role_id'] = $order->role_id;
        $serviceStateData['service_id'] = $order->service_id;
        $serviceStateData['source_country_id'] = $order->source_country_id;
        $serviceStateData['destination_country_id'] = $order->destination_country_id;
        $serviceStateData['amount'] = $order->amount;
        $serviceStateData['enable'] = true;
        $serviceStates = Business::serviceStat()->findWhere($serviceStateData);
        if (! $serviceStates) {
            throw new Exception('Service State Data not found');
        }
        $serviceState = $serviceStates->toArray();
        $serviceStateData['service_stat_id'] = $serviceState['id'];
        $charge_break_down = Business::chargeBreakDown()->findWhere($serviceStateData);
        $serviceState = $serviceState['service_stat_data'];
        if ($charge_break_down) {
            $serviceStateJsonData['charge'] = $charge_break_down->charge;
            $serviceStateJsonData['discount'] = $charge_break_down->discount;
            $serviceStateJsonData['commission'] = $charge_break_down->commission;
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

    public function serviceStateData($order): array
    {
        if (!is_array($order)) {
            $inputs = $order->toArray();
            $inputs['role_id'] = $order->user->roles[0]->getKey();
        }

        $serviceCost = $this->cost($inputs);

        return Arr::only($serviceCost, [
                "charge", "discount", "commission", "charge_refund",
                "discount_refund", "commission_refund", "charge_break_down_id",
                "service_stat_id", "total_amount"
            ]
        );
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
        $serviceCost['service_stat_id'] = $serviceStat->getKey();
        $serviceCost['charge_refund'] = $serviceStat->service_stat_data['charge_refund'] ?? 'no';
        $serviceCost['discount_refund'] = $serviceStat->service_stat_data['discount_refund'] ?? 'no';
        $serviceCost['commission_refund'] = $serviceStat->service_stat_data['commission_refund'] ?? 'no';

        $baseCurrency = ($inputs['reverse']) ? $serviceCost['output'] : $serviceCost['input'];

        $baseAmount = ($inputs['reverse']) ? $serviceCost['converted'] : $inputs['amount'];

        $localeAmount = (! $inputs['reverse']) ? $serviceCost['converted'] : $inputs['amount'];

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

        if ($inputs['source_country_id'] != $inputs['destination_country_id']) {

            if (isset($serviceStatData['local_currency_lower_limit']) && is_numeric($serviceStatData['local_currency_lower_limit'])) {
                if ($localeAmount < floatval($serviceStatData['local_currency_lower_limit'])) {
                    throw new BusinessException(__('business::messages.service_stat.local_currency_below_lower_limit'));
                }
            }

            if (isset($serviceStatData['local_currency_higher_limit']) && is_numeric($serviceStatData['local_currency_higher_limit'])) {
                if ($localeAmount > floatval($serviceStatData['local_currency_higher_limit'])) {
                    throw new BusinessException(__('business::messages.service_stat.local_currency_upper_limit_exceed'));
                }
            }
        }

        $chargeBreakDown = Business::chargeBreakDown()->findWhere([
            'enabled' => true,
            'service_id' => $inputs['service_id'],
            'service_stat_id' => $serviceStat->getKey(),
            'amount' => $baseAmount,
        ]);

        if ($chargeBreakDown) {
            $serviceStatData['charge'] = $chargeBreakDown->charge;
            $serviceStatData['discount'] = $chargeBreakDown->discount;
            $serviceStatData['commission'] = $chargeBreakDown->commission;
        }

        $serviceCost['charge_break_down_id'] = $chargeBreakDown?->getKey() ?? null;

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
