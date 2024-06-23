<?php

namespace App\Services;

use App\Jobs\PostModelCsvFiles;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService {

    public function search(array $data)
    {
        return Product::query()
            ->where("code", data_get($data, "query"))
            ->orWhere("description", "LIKE", '%' . data_get($data, "query") . '%')
            ->orderBy(
                data_get($data, "order_by", "id"),
                data_get($data, "direction", "DESC"),
            );
    }

    public function create(array $data) : Product
    {
        return Product::create($data);
    }

    public function postCsv(string $filename)
    {
        PostModelCsvFiles::dispatch($filename, Product::class)
            ->onQueue("csv");
    }

    public function findByCode(string $code) : Product
    {
        return Product::where("code", $code)->firstOrFail();
    }

    public function deleteByCode(string $code) : bool
    {
        return Product::where("code", $code)->delete();
    }

    public function update(Product $product, array $data)
    {
        DB::transaction(function() use ($product, $data) {
            
            $product->update([
                ...$data
            ]);

            $product->logs()
                ->create([
                    'product_update_payload'    => $product->logAttributes()
                ]);
        });
    }
}
