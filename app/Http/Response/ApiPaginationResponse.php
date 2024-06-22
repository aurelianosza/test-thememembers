<?php

namespace App\Http\Response;

use Illuminate\Contracts\Database\Eloquent\Builder;

class ApiPaginationResponse {
    
    const MAX_RECORDS = 15;

    private array $meta = [];
    private $records;

    public function __construct(
        private ApiResponse $respsonse,
        private int $page = 1
    )
    {
        if(!$this->page)
        {
            $this->page = request('page', 1);
        }
    }

    public function setData(Builder $query, $resourceJson = null) : ApiPaginationResponse
    {
        $rows = $query
            ->paginate(self::MAX_RECORDS);

        $this->meta = [
            'total'         => $rows->total(),
            'per_page'      => self::MAX_RECORDS,
            'current_page'  => $rows->currentPage(),
            'last_page'     => $rows->lastPage()
        ];

        $this->records = $resourceJson ? $resourceJson::collection($rows->items()) : $rows->items();

        return $this;
    }

    public function setMetaData(array $data) : ApiPaginationResponse
    {
        foreach($data as $key => $value)
        {
            data_set($this->meta, $key, $value);
        }

        return $this;
    }

    public function respond()
    {
        $this->respsonse
            ->setData([
                'meta'      => $this->meta,
                'records'   => $this->records
            ]);

        return $this->respsonse->respond();
    }

}
