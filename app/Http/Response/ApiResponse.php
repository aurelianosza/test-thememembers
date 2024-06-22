<?php

namespace App\Http\Response;

use Illuminate\Http\Response;
use App\Http\Response\Traits\CanUpdateStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse {

    use CanUpdateStatus;

    const STATUS_SUCCESS            = 'success';
    const STATUS_ERROR              = 'error';
    const STATUS_UNATHORIZED        = 'unauthorized';

    const STATUSES = [
        Response::HTTP_OK                   => self::STATUS_SUCCESS, 
        Response::HTTP_CREATED              => self::STATUS_SUCCESS, 
        Response::HTTP_BAD_REQUEST          => self::STATUS_ERROR,       
        Response::HTTP_FORBIDDEN            => self::STATUS_UNATHORIZED,
        Response::HTTP_UNPROCESSABLE_ENTITY => self::STATUS_ERROR,
        Response::HTTP_NOT_FOUND            => self::STATUS_ERROR,
        Response::HTTP_TOO_MANY_REQUESTS    => self::STATUS_ERROR
    ];

    private $response;
    private array $data;
    private int $status;

    public function __construct()
    {
        $this->response = response();
        $this->data = [];
        $this->status = 0;
    }

    public function getStatusCodeText()
    {
        return self::STATUSES[$this->status];
    }

    public function setData(array  | JsonResource $data) : self
    {
        if($data instanceof JsonResource)
        {
            $data = $data->resolve();
        }

        $this->data = $data;

        return $this;
    }

    public function setStatus(int $status = Response::HTTP_OK) : self
    {
        $this->status = $status;

        return $this;
    }

    public function respond()
    {
        if(!$this->status)
        {
            $this->success();
        }

        return $this->response
            ->json(array_merge(
                [
                    'status' => $this->getStatusCodeText()
                ],
                $this->data,
            ), $this->status);
    }
}
