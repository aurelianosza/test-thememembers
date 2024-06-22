<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Models\Buyer;
use App\Services\BuyerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuyerPostCsvController extends Controller
{
    const CONTROLLER_PREFIX = "/buyers/post-csv";

    public function __invoke(
        Request $request,
        BuyerService $service,
        ApiResponse $response
    )
    {            
        $file = Storage::drive("postable_csv_model_files")
            ->put('buyers', $request->file("file"));

        $service->postCsv($file);

        return $response
            ->success()
            ->setData([
                "message"   => __("messages.cruds.file_csv_uploaded", [
                    "model"     => __("models." . Buyer::class . ".name")
                ])
            ])
            ->respond();
        
    }
}
