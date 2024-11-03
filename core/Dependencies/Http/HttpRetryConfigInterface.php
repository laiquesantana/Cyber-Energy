<?php

namespace Saas\Project\Dependencies\Http;

interface HttpRetryConfigInterface
{
    public function getRetryDecider(): callable;

    public function getRetryDelay(): callable;
}
