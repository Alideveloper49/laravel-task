<?php

namespace App\Repositories;

use App\Models\StockPrice;
use App\Repositories\Interface\StockPriceRepositoryInterface;

class StockPriceRepository implements StockPriceRepositoryInterface
{
    protected $model;

    public function __construct(StockPrice $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function getPricesByCompany(int $companyId)
    {
        return $this->model->where('company_id', $companyId)->orderBy('date', 'desc')->get();
    }

    public function getPriceBetweenDates(int $companyId, string $start, string $end)
    {
        return $this->model
            ->where('company_id', $companyId)
            ->whereDate('date', '<=', $start)
            ->whereDate('date', '>=', $end)
            ->orderBy('date', 'asc')
            ->get();
    }
}