<?php

namespace App\Adapters\Http;

use Saas\Project\Dependencies\Http\HttpRetryConfigInterface;
use Saas\Project\Dependencies\Http\Collections\HeaderCollection;
use Saas\Project\Dependencies\Http\Entities\HttpResponse;
use Saas\Project\Dependencies\Http\Exceptions\RequestFailureException;
use Saas\Project\Dependencies\Http\HttpClientInterface;
use Throwable;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class ClientAdapter implements HttpClientInterface
{
    private GuzzleClient $client;

    public function __construct(array $config = [], ?HttpRetryConfigInterface $httpRetryConfig = null)
    {
        if ($httpRetryConfig) {
            $handlerStack = HandlerStack::create(new CurlHandler());
            $handlerStack->push(
                Middleware::retry($httpRetryConfig->getRetryDecider(), $httpRetryConfig->getRetryDelay())
            );
            $config['handler'] = $handlerStack;
        }
        $this->client = new GuzzleClient($config);
    }

    public function post(
        string $endpoint,
        $data,
        array  $headers = [],
        string $contentType = 'application/json'
    ): HttpResponse
    {
        try {
            $response = $this->client->post(
                $endpoint,
                [
                    'headers' => array_merge(
                        $headers,
                        [
                            'Content-Type' => $contentType,
                        ]
                    ),
                    'json' => $data,
                ]
            );

            return new HttpResponse(
                $response->getStatusCode(),
                $response->getBody()->getContents(),
                new HeaderCollection($response->getHeaders())
            );
        } catch (Throwable $exception) {
            $contents = json_decode($exception->getResponse()->getBody()->getContents());
            $message = $contents->error->message->value;

            throw new RequestFailureException("Failed to execute post request - $message", $exception);
        }
    }

    public function get(string $endpoint, array $headers = [], bool $httpError = true): string
    {
        try {
            $response = $this->client->get(
                $endpoint,
                [
                    'http_errors' => $httpError,
                    'headers' => array_merge(
                        $headers,
                        [
                            'ContentType' => 'application/json',
                        ]
                    )
                ]
            );
        } catch (Throwable $exception) {
            throw (new RequestFailureException("Failed to execute get request", $exception, 500))
                ->setEndpoint($endpoint);
        }

        if ($response->getStatusCode() !== 200) {
            throw new RequestFailureException(
                $response->getBody()->getContents(),
                null,
                $response->getStatusCode()
            );
        }

        return $response->getBody()->getContents();
    }
}