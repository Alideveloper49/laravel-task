<?php
namespace App\Services;

use App\Repositories\Interface\StockPriceRepositoryInterface;
use Carbon\Carbon;

class StockPriceService
{
    protected $repository;

    public function __construct(StockPriceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    public function getHistorical(int $companyId)
    {
        $prices = $this->repository->getPricesByCompany($companyId);

        $latest = $prices->first();
        $periods = ['1D', '1M', '3M', '6M', 'YTD', '1Y', '3Y', '5Y', '10Y', 'MAX'];
        $results = [];

        foreach ($periods as $period) {
            $pastPrice = null;

            switch ($period) {
                case '1D':
                    $pastPrice = $prices->where('date', Carbon::parse($latest->date)->subDay())
                        ->last()?->price ?? null;
                    break;
                case '1M':
                    $pastPrice = $prices->where('date', '>=', Carbon::parse($latest->date)->subMonth())
                        ->last()?->price ?? null;
                    break;
                case '3M':
                    $pastPrice = $prices->where('date', '>=', Carbon::parse($latest->date)->subMonths(3))
                        ->last()?->price ?? null;
                    break;
                case '6M':
                    $pastPrice = $prices->where('date', '>=', Carbon::parse($latest->date)->subMonths(6))
                        ->last()?->price ?? null;
                    break;
                case 'YTD':
                    $yearStart = Carbon::parse($latest->date)->startOfYear();
                    $pastPrice = $prices->where('date', '>=', $yearStart)
                        ->last()?->price ?? null;
                    break;
                case '1Y':
                    $pastPrice = $prices->where('date', '>=', Carbon::parse($latest->date)->subYear())
                        ->last()?->price ?? null;
                    break;
                case '3Y':
                    $pastPrice = $prices->where('date', '>=', Carbon::parse($latest->date)->subYears(3))
                        ->last()?->price ?? null;
                    break;
                case '5Y':
                    $pastPrice = $prices->where('date', '>=', Carbon::parse($latest->date)->subYears(5))
                        ->last()?->price ?? null;
                    break;
                case '10Y':
                    $pastPrice = $prices->where('date', '>=', Carbon::parse($latest->date)->subYears(10))
                        ->last()?->price ?? null;
                    break;
                case 'MAX':
                    $pastPrice = $prices->last()?->price ?? null;
                    break;
            }

            if ($pastPrice) {
                $percentage = (($latest->price / $pastPrice) - 1) * 100;
                $results[$period] = [
                    'period' => $period,
                    'price' => $latest->price,
                    'percentage_change' => round($percentage, 2),
                ];
            } else {
                $results[$period] = null;
            }
        }

        return $results;
    }

    public function getCustomPeriod(int $companyId, string $start, string $end)
    {
        $prices = $this->repository->getPriceBetweenDates($companyId, $start, $end);

        if ($prices->isEmpty()) {
            return null;
        }

        $startPrice = $prices->first()->price;
        $endPrice = $prices->last()->price;
        $percentage = (($endPrice / $startPrice) - 1) * 100;

        return [
            'start_price' => $startPrice,
            'end_price' => $endPrice,
            'percentage_change' => round($percentage, 2),
        ];
    }
}
