<?php

namespace Saas\Project\Modules\Generics\Responses;


use Saas\Project\Modules\Generics\Entities\Status;

interface ResponseInterface
{
    public function getStatus(): Status;
}
