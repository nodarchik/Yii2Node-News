<?php

namespace app\clients;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class AuthApiClient extends BaseApiClient
{
    /**
     * @var string The base URL for the Auth API
     */
    protected $baseUrl;

    /**
     * AuthApiClient constructor.
     *
     * @param Client $client
     * @param string $baseUrl
     * @param array $config
     */
    public function __construct(Client $client, string $baseUrl, array $config = [])
    {
        $this->baseUrl = $baseUrl;
        parent::__construct($client, $config);
    }

    /**
     * Sends a login request to the Auth API.
     *
     * @param string $email
     * @param string $password
     * @return array
     * @throws Exception|GuzzleException
     */
    public function login(string $email, string $password): array
    {
        $url = $this->baseUrl . '/v1/login';
        return $this->sendRequest('POST', $url, [
            'email' => $email,
            'password' => $password,
        ]);
    }

    /**
     * Sends a logout request to the Auth API.
     *
     * @param string $token
     * @return array
     * @throws Exception|GuzzleException
     */
    public function logout(string $token): array
    {
        $url = $this->baseUrl . '/v1/logout';
        return $this->sendRequest('POST', $url, [], $token);
    }

    /**
     * Validates a token using the Auth API.
     *
     * @param string $token
     * @return array
     * @throws Exception|GuzzleException
     */
    public function validateToken(string $token): array
    {
        $url = $this->baseUrl . '/v1/validate-token';
        return $this->sendRequest('POST', $url, [], $token);
    }
}
