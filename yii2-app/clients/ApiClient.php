<?php

namespace app\clients;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Yii;
use yii\base\Component;

class BaseApiClient extends Component
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * BaseApiClient constructor.
     * @param Client $client
     * @param array $config
     */
    public function __construct(Client $client, array $config = [])
    {
        $this->client = $client;
        parent::__construct($config);
    }

    /**
     * Sends an HTTP request to the API.
     *
     * @param string $method
     * @param string $url
     * @param array $data
     * @param string|null $token
     * @return array
     * @throws Exception|GuzzleException
     */
    protected function sendRequest(string $method, string $url, array $data = [], ?string $token = null): array
    {
        $options = [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];

        if ($token) {
            $options['headers']['Authorization'] = "Bearer $token";
        }

        if (!empty($data)) {
            $options['json'] = $data;
        }

        try {
            $response = $this->client->request($method, $url, $options);
            $body = $response->getBody();
            $responseData = json_decode($body->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Failed to decode JSON response.');
            }

            return $responseData;
        } catch (RequestException $e) {
            Yii::error("Request to $url failed: {$e->getMessage()}", __METHOD__);
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
}
