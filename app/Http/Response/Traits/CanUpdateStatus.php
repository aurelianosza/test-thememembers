<?php

namespace App\Http\Response\Traits;

use Illuminate\Http\Response;

trait CanUpdateStatus {

    public function setStatus(int $status = Response::HTTP_OK) : self
    {
        $this->status = $status;

        return $this;
    }

    public function success() : self
    {
       return $this->setStatus(Response::HTTP_OK);
    }

    public function created() : self
    {
       return $this->setStatus(Response::HTTP_CREATED);
    }

    public function unauthorized() : self
    {
        return $this->setStatus(Response::HTTP_FORBIDDEN);
    }

    public function error() : self
    {
        return $this->setStatus(Response::HTTP_BAD_REQUEST);
    }   

    public function validationError() : self
    {
        return $this->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

}
