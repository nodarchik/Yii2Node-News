<?php

namespace app\clients;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class UserApiClient extends BaseApiClient
{
    /**
     * @var string The base URL for the User API
     */
    protected $baseUrl;

    /**
     * UserApiClient constructor.
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
     * Registers a new user.
     *
     * @param array $data
     * @return array
     * @throws Exception|GuzzleException
     */
    public function registerUser(array $data): array
    {
        $url = $this->baseUrl . '/register';
        return $this->sendRequest('POST', $url, $data);
    }

    /**
     * Retrieves all users.
     *
     * @param string $token
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getAllUsers(string $token): array
    {
        $url = $this->baseUrl . '/users';
        return $this->sendRequest('GET', $url, [], $token);
    }

    /**
     * Updates a user by ID.
     *
     * @param string $id
     * @param array $data
     * @param string $token
     * @return array
     * @throws Exception|GuzzleException
     */
    public function updateUser(string $id, array $data, string $token): array
    {
        $url = $this->baseUrl . "/users/{$id}";
        return $this->sendRequest('PUT', $url, $data, $token);
    }

    /**
     * Deletes a user by ID.
     *
     * @param string $id
     * @param string $token
     * @return array
     * @throws Exception|GuzzleException
     */
    public function deleteUser(string $id, string $token): array
    {
        $url = $this->baseUrl . "/users/{$id}";
        return $this->sendRequest('DELETE', $url, [], $token);
    }

    /**
     * Retrieves a user by ID.
     *
     * @param string $id
     * @param string $token
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getUserById(string $id, string $token): array
    {
        $url = $this->baseUrl . "/users/$id";
        return $this->sendRequest('GET', $url, [], $token);
    }
}
