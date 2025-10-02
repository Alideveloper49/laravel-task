<?php

namespace App\Imports;

use App\Services\StockPriceService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
class StockPricesImport implements ToModel, WithStartRow, ShouldQueue, WithChunkReading
{
    protected $companyId;
    protected $service;
    public function startRow(): int
    {
        return 10; // Skip the first 10 rows (headers, metadata, etc.)
    }

    // Constructor receives company ID from controller
    public function __construct(int $companyId)
    {
        $this->companyId = $companyId;
        $this->service = app(StockPriceService::class);
    }

    /**
     * @param array $row
     */
    public function model(array $row): mixed
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        // Skip empty rows
        if (empty($row[0]) || empty($row[1])) {
            return null;
        }

        $excelDate = $row[0];

        // Check if it's an Excel serial date (numeric value)
        if (is_numeric($excelDate)) {
            $date = Date::excelToDateTimeObject($excelDate);
            $carbonDate = Carbon::instance($date);
            $formattedDate = $carbonDate->format('Y-m-d');
        }

        $data = [
            'company_id' => $this->companyId,
            'date' => $formattedDate,
            'price' => $row[1],
        ];

        return $this->service->store($data);
    }

    public function chunkSize(): int
    {
        return 1000; // process 1000 rows per job
    }
}
