<?php

namespace App\Repositories\Interface;

interface StockPriceRepositoryInterface
{
    public function create(array $data);
    public function getPricesByCompany(int $companyId);
    public function getPriceBetweenDates(int $companyId,string $start, string $end);
}
