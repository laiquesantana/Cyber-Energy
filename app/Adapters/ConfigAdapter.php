<?php

namespace App\Adapters;

use Saas\Project\Dependencies\Config\ConfigInterface;
use Saas\Project\Dependencies\Config\Exceptions\NullConfigException;

class ConfigAdapter implements ConfigInterface
{
    public function getOpenAiKey(): string
    {
        return $this->getConfig('OPENAI_API_KEY');
    }

    public function getOpenAiModel(): string
    {
        return $this->getConfig('OPENAI_MODEL');
    }

    public function getOpenFilter(): string
    {
        return $this->getConfig('AI_FILTER');
    }


    private function getConfig(string $name, string $defaultValue = null): string
    {
        $config = env($name, $defaultValue);
        if (is_null($config)) {
            throw new NullConfigException(
                sprintf("The environment variable used is not set in the project: %s", $name)
            );
        }

        return $config;
    }
}
