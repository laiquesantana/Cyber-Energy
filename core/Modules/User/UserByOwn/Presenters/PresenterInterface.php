<?php

namespace Saas\Project\Modules\User\UserByOwn\Presenters;

interface PresenterInterface
{
    public function present(): PresenterInterface;

    public function toArray(): array;
}
