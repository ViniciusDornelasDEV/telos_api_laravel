<?php

namespace Modules\Product\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Product\Jobs\ImportProductsFromCsvJob;

class ProductImportController extends Controller
{
    public function import(Request $request): JsonResponse
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'file'        => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('file')->store('imports');

        ImportProductsFromCsvJob::dispatch(
            $path,
            $data['supplier_id'],
            auth()->user()
        );

        return ApiResponse::success([
            'message' => 'Arquivo recebido. O processamento será feito em segundo plano.'
        ]);
    }
}
