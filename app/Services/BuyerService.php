<?php

namespace App\Services;

use App\Jobs\PostModelCsvFiles;
use App\Models\Buyer;

class BuyerService
{

    protected $buyer;
    public function __construct(Buyer $buyer)
    {
        $this->buyer = $buyer;
    }

    public function search(array $data)
    {
        return Buyer::query()
            ->where("document", data_get($data, "query"))
            ->orWhere("name", "LIKE", '%' . data_get($data, "query") . '%')
            ->orderBy(
                data_get($data, "order_by", "id"),
                data_get($data, "direction", "DESC"),
            );
    }

    public function create(array $data): Buyer
    {
        return $this->buyer->create($data);
    }

    public function postCsv(string $filename)
    {
        PostModelCsvFiles::dispatch($filename, Buyer::class)
            ->onQueue("csv");
    }

    public function update(Buyer $buyer, array $data)
    {
        return $buyer->update($data);
    }

    public function findByDocument(string $document): Buyer
    {
        return $this->buyer->where("document", $document)->first();
    }
}
