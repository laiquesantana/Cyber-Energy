<?php

declare(strict_types=1);

namespace Saas\Project\Packages\OpenAi\Chat;

use OpenAI\Client;
use Saas\Project\Dependencies\Config\ConfigInterface;

class Api
{
    private Client $client;
    private ConfigInterface $config;

    public function __construct(ConfigInterface  $config)
    {
        $this->config = $config;
        $this->client =  \OpenAI::client($config->getOpenAiKey());
    }

    public function getResponse(string $prompt): string
    {

        $response = $this->client->completions()->create([
            'model' => $this->config->getOpenAiModel(),
            'prompt' => $prompt,
            'max_tokens' => 1500,
        ]);

        return $response['choices'][0]['text'] ?? 'No response';
    }
}
