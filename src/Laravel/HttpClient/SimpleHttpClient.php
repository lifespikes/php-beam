<?php

namespace LifeSpikes\PhpBeam\Laravel\HttpClient;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Str;
use LifeSpikes\PhpBeam\Laravel\HttpClient\Enums\HttpMethod;
use LifeSpikes\PhpBeam\Laravel\HttpClient\Exceptions\SimpleHttpClientException;

/**
 * @method Response post(string $endpoint, array $body = [])
 * @method Response get(string $endpoint, array $query = [])
 * @method Response put(string $endpoint, array $body = [])
 * @method Response patch(string $endpoint, array $body = [])
 * @method Response delete(string $endpoint, array $query = [])
 */
abstract class SimpleHttpClient
{
    public string $baseUri;

    /**
     * @throws SimpleHttpClientException
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (($verb = HttpMethod::tryFrom(Str::upper($name)))) {
            return $this->dispatchRequest($verb, ...$arguments);
        }

        return $this->{$name}(...$arguments);
    }

    /**
     * Assign headers to all requests
     */
    public function headers(HttpMethod $method, string $path, array $body = []): array
    {
        return [];
    }

    /**
     * Dispatch a request and ensure the correct API keys are
     * being sent, regardless of what the environment is
     * set to, since some endpoints require live keys.
     *
     * @throws SimpleHttpClientException
     */
    protected function dispatchRequest(HttpMethod $method, string $path, array $body = []): Response
    {
        $request = [
            'options'   => [
                'headers'   => $this->headers($method, $path, $body),
                'base_uri'  => $this->baseUri,
            ],
            'method'    => $method->value,
            'path'      => $path,
            'body'      => purge_null($body)
        ];

        $response = Http::withOptions($request['options'])
            ->{$request['method']}($request['path'], $request['body']);

        return $this->handleResponse($response, $request);
    }

    /**
     * Handles response, if an error is detected throws an exception.
     *
     *
     * @throws SimpleHttpClientException
     */
    protected function handleResponse(Response $response, array $requestContext): Response
    {
        $status = $response->status();
        $body   = $response->json();

        if (
            (isset($body['success']) && ! $body['success']) ||
            ($status < 200 || $status > 200)
        ) {
            throw (new SimpleHttpClientException('Non-200 or failure response (' . $response->status() . '): ' . $response->body()))
                ->setContext([
                    'request'   => $requestContext,
                    'response'  => [
                        'status'    => $response->status(),
                        'headers'   => $response->headers(),
                        'body'      => $response->body()
                    ]
                ]);
        }

        return $response;
    }
}
