<?php

namespace app\services;

use app\clients\NewsApiClient;
use app\models\News;
use GuzzleHttp\Exception\GuzzleException;
use yii\base\Component;
use yii\base\InvalidConfigException;

class NewsService extends Component
{
    /**
     * @var NewsApiClient
     */
    private $newsApiClient;

    /**
     * NewsService constructor.
     *
     * @param NewsApiClient $newsApiClient
     * @param array $config
     */
    public function __construct(NewsApiClient $newsApiClient, array $config = [])
    {
        $this->newsApiClient = $newsApiClient;
        parent::__construct($config);
    }

    /**
     * Creates a new news item.
     *
     * @param array $data
     * @param string $token
     * @return News
     * @throws InvalidConfigException|GuzzleException
     */
    public function createNews(array $data, string $token): News
    {
        $apiResponse = $this->newsApiClient->createNews($data, $token);

        if (isset($apiResponse['_id'])) {
            return new News([
                'id' => $apiResponse['_id'],
                // ... other fields
            ]);
        }

        throw new InvalidConfigException('News creation failed.');
    }

    /**
     * Retrieves all news items.
     *
     * @param string $token
     * @return News[]
     * @throws GuzzleException
     */
    public function getAllNews(string $token): array
    {
        $apiResponse = $this->newsApiClient->getAllNews($token);
        $newsItems = [];

        foreach ($apiResponse as $newsData) {
            $newsItems[] = new News([
                'id' => $newsData['_id'],
                'title' => $newsData['title'],
                'content' => $newsData['content'],
            ]);
        }

        return $newsItems;
    }

    /**
     * Retrieves a single news item by ID.
     *
     * @param string $id
     * @param string $token
     * @return News
     * @throws InvalidConfigException|GuzzleException
     */
    public function getNewsById(string $id, string $token): News
    {
        $apiResponse = $this->newsApiClient->getNewsById($id, $token);

        if (isset($apiResponse['_id'])) {
            return new News([
                'id' => $apiResponse['_id'],
                'title' => $apiResponse['title'],
                'content' => $apiResponse['content'],
            ]);
        }

        throw new InvalidConfigException('News retrieval failed.');
    }

    /**
     * Updates a news item by ID.
     *
     * @param string $id
     * @param array $data
     * @param string $token
     * @return News
     * @throws InvalidConfigException|GuzzleException
     */
    public function updateNews(string $id, array $data, string $token): News
    {
        $apiResponse = $this->newsApiClient->updateNews($id, $data, $token);

        if (isset($apiResponse['_id'])) {
            return new News([
                'id' => $apiResponse['_id'],
                'title' => $apiResponse['title'],
                'content' => $apiResponse['content'],
            ]);
        }

        throw new InvalidConfigException('News update failed.');
    }

    /**
     * Deletes a news item by ID.
     *
     * @param string $id
     * @param string $token
     * @return bool
     * @throws InvalidConfigException|GuzzleException
     */
    public function deleteNews(string $id, string $token): bool
    {
        $apiResponse = $this->newsApiClient->deleteNews($id, $token);

        // Assuming a successful deletion returns a response with a 'success' field
        if (isset($apiResponse['success']) && $apiResponse['success']) {
            return true;
        }

        throw new InvalidConfigException('News deletion failed.');
    }
}
