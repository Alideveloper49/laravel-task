<?php

namespace App\Http\Controllers;

use App\Imports\StockPricesImport;
use App\Models\Company;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class StockImportController extends Controller
{
    public function index(): View
    {
        return view('index');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|unique:companies,name',
            'file' => 'required|file',
        ]);

        try {
            DB::beginTransaction();
            $company = Company::firstOrCreate(['name' => $request->name]);

            Excel::queueImport(
                new StockPricesImport($company->id),
                $request->file('file'),
                'local'
            );
            DB::commit();
            $msg = [
                'success' => 1,
                'msg' => 'File uploaded successfully!.'
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());
            $msg = [
                'success' => 0,
                'msg' => 'File upload failed.'
            ];
        }

        return response()->json($msg);
    }
}
