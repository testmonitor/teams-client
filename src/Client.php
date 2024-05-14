<?php

namespace TestMonitor\Teams;

use Psr\Http\Message\ResponseInterface;
use TestMonitor\Teams\Exceptions\Exception;
use TestMonitor\Teams\Exceptions\NotFoundException;
use TestMonitor\Teams\Exceptions\ValidationException;
use TestMonitor\Teams\Exceptions\FailedActionException;
use TestMonitor\Teams\Exceptions\UnauthorizedException;

class Client
{
    use Actions\PostMessages;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected string $webhookUrl;

    /**
     * Create a new client instance.
     *
     * @param string $webhookUrl
     */
    public function __construct(string $webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * Returns an Guzzle client instance.
     *
     * @throws \TestMonitor\Teams\Exceptions\UnauthorizedException
     *
     * @return \GuzzleHttp\Client
     */
    protected function client()
    {
        if (empty($this->webhookUrl)) {
            throw new UnauthorizedException();
        }

        return $this->client ?? new \GuzzleHttp\Client([
            'base_uri' => $this->webhookUrl,
            'http_errors' => false,
            'timeout' => 10,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * @param \GuzzleHttp\Client $client
     */
    public function setClient(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    /**
     * Make a POST request to the Microsoft Teams servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Teams\Exceptions\FailedActionException
     * @throws \TestMonitor\Teams\Exceptions\NotFoundException
     * @throws \TestMonitor\Teams\Exceptions\UnauthorizedException
     * @throws \TestMonitor\Teams\Exceptions\ValidationException
     *
     * @return mixed
     */
    protected function post(string $uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * Make request to the Microsoft Teams servers and return the response.
     *
     * @param string $verb
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Teams\Exceptions\FailedActionException
     * @throws \TestMonitor\Teams\Exceptions\NotFoundException
     * @throws \TestMonitor\Teams\Exceptions\UnauthorizedException
     * @throws \TestMonitor\Teams\Exceptions\ValidationException
     *
     * @return mixed
     */
    protected function request($verb, $uri, array $payload = [])
    {
        $response = $this->client()->request(
            $verb,
            $uri,
            $payload
        );

        if (! in_array($response->getStatusCode(), [200, 201, 203, 204, 206])) {
            return $this->handleRequestError($response);
        }

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    /**
     * @param  \Psr\Http\Message\ResponseInterface $response
     *
     * @throws \TestMonitor\Teams\Exceptions\ValidationException
     * @throws \TestMonitor\Teams\Exceptions\NotFoundException
     * @throws \TestMonitor\Teams\Exceptions\FailedActionException
     * @throws \Exception
     *
     * @return void
     */
    protected function handleRequestError(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 422) {
            throw new ValidationException(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() == 404 ||
            $response->getStatusCode() == 405 ||
            $response->getStatusCode() == 410) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() == 401 || $response->getStatusCode() == 403) {
            throw new UnauthorizedException();
        }

        if ($response->getStatusCode() == 400 || $response->getStatusCode() == 503) {
            throw new FailedActionException((string) $response->getBody());
        }

        throw new Exception((string) $response->getStatusCode());
    }
}
