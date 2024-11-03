<?php

namespace Saas\Project\Modules\Generics\Responses;


use Saas\Project\Modules\Generics\Entities\Status;

class Response implements ResponseInterface
{
    private Status $status;

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): Response
    {
        $this->status = $status;
        return $this;
    }
}
