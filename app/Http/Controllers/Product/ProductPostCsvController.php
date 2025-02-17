<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductPostCsvController extends Controller
{
    const CONTROLLER_PREFIX = "/products/post-csv";

    public function __invoke(
        Request $request,
        ProductService $service,
        ApiResponse $response
    )
    {
        $file = Storage::drive("postable_csv_model_files")
            ->put('products', $request->file("file"));

        $service->postCsv($file);

        return $response
            ->success()
            ->setData([
                "message"   => __("messages.cruds.file_csv_uploaded", [
                    "model"     => __("models." . Product::class . ".name")
                ])
            ])
            ->respond();
    }
}
