<?php

namespace Saas\Project\Dependencies\Config;

interface ConfigInterface
{
    public function getOpenAiKey(): string;

    public function getOpenAiModel(): string;

    public function getOpenFilter(): string;

}
