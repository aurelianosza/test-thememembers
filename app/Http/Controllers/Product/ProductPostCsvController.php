<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductPostCsvController extends Controller
{
    public function __invoke(Request $request, ProductService $service)
    {
        try {
            
            $arquivo = Storage::drive("postable_csv_model_files")
                ->put('products', $request->file("file"));

            $service->postCsv($arquivo);

            return $this->success("File uploaded successfully", '', 201);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400);
        }


    }
}
