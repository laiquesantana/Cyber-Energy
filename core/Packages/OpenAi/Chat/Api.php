<?php

namespace Saas\Project\Packages\OpenAi\Chat;

use App\Adapters\Http\ClientAdapter;
use Saas\Project\Dependencies\Config\ConfigInterface;
use Saas\Project\Dependencies\Http\Exceptions\RequestFailureException;

class Api
{
    private ClientAdapter $client;
    private ConfigInterface $config;

    public function __construct(ClientAdapter $client, ConfigInterface $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function getResponse(string $prompt): string
    {
        $endpoint = 'https://api.openai.com/v1/chat/completions';
        $headers = [
            'Authorization' => 'Bearer ' . $this->config->getOpenAiKey(),
        ];

        $data = [
            'model' => $this->config->getOpenAiModel(),
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 15000,
        ];

        try {
            $response = $this->client->post($endpoint, $data, $headers);

            return json_decode($response->getBody(), true)['choices'][0]['message']['content'] ?? 'No response';
        } catch (RequestFailureException $exception) {
            throw new RequestFailureException("Failed to retrieve response from OpenAI: {$exception->getMessage()}", $exception->getCode(), $exception);
        }
    }
}
