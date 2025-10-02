<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StockPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockPriceController extends Controller
{
    protected $service;

    //Constructor load Class StockPriceService 
    public function __construct(StockPriceService $service)
    {
        $this->service = $service;
    }
    public function historical(int $companyId): JsonResponse
    {
        $data = $this->service->getHistorical($companyId);
        return $this->sendResponse($data);
    }
    public function custom(Request $request, int $companyId): JsonResponse
    {
        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $data = $this->service->getCustomPeriod($companyId, $data['start_date'], $data['end_date']);
        return $this->sendResponse($data);
    }
}
