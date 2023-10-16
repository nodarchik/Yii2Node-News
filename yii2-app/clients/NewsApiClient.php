<?php

namespace app\clients;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class NewsApiClient extends BaseApiClient
{
    /**
     * @var string The base URL for the News API
     */
    protected $baseUrl;

    /**
     * NewsApiClient constructor.
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
     * Sends a request to create a new news item.
     *
     * @param array $data
     * @param string $token
     * @return array
     * @throws Exception|GuzzleException
     */
    public function createNews(array $data, string $token): array
    {
        $url = $this->baseUrl . '/news';
        return $this->sendRequest('POST', $url, $data, $token);
    }

    /**
     * Retrieves all news items.
     *
     * @param string $token
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getAllNews(string $token): array
    {
        $url = $this->baseUrl . '/news';
        return $this->sendRequest('GET', $url, [], $token);
    }

    /**
     * Retrieves a single news item by ID.
     *
     * @param string $id
     * @param string $token
     * @return array
     * @throws Exception|GuzzleException
     */
    public function getNewsById(string $id, string $token): array
    {
        $url = $this->baseUrl . "/news/{$id}";
        return $this->sendRequest('GET', $url, [], $token);
    }

    /**
     * Updates a news item by ID.
     *
     * @param string $id
     * @param array $data
     * @param string $token
     * @return array
     * @throws Exception|GuzzleException
     */
    public function updateNews(string $id, array $data, string $token): array
    {
        $url = $this->baseUrl . "/news/{$id}";
        return $this->sendRequest('PUT', $url, $data, $token);
    }

    /**
     * Deletes a news item by ID.
     *
     * @param string $id
     * @param string $token
     * @return array
     * @throws Exception|GuzzleException
     */
    public function deleteNews(string $id, string $token): array
    {
        $url = $this->baseUrl . "/news/{$id}";
        return $this->sendRequest('DELETE', $url, [], $token);
    }
}
