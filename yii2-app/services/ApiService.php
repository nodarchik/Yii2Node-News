<?php

namespace app\services;

use GuzzleHttp\Client;
use yii\base\Component;

class ApiService extends Component
{
    private $client;
    private $baseUri = 'http://localhost:3002/api/';

    public function init()
    {
        parent::init();
        $this->client = new Client(['base_uri' => $this->baseUri]);
    }

    // User Routes
    public function registerUser($username, $password, $confirmPassword)
    {
        $response = $this->client->post('register', [
            'json' => [
                'username' => $username,
                'password' => $password,
                'confirmPassword' => $confirmPassword,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getUsers()
    {
        $response = $this->client->get('');
        return json_decode($response->getBody(), true);
    }

    public function updateUser($id, $username, $password)
    {
        $response = $this->client->put($id, [
            'json' => ['username' => $username, 'password' => $password],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function deleteUser($id)
    {
        $response = $this->client->delete($id);
        return json_decode($response->getBody(), true);
    }

    // News Routes
    public function createNews($title, $content)
    {
        $response = $this->client->post('news', [
            'json' => ['title' => $title, 'content' => $content],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function fetchNews()
    {
        $response = $this->client->get('news');
        return json_decode($response->getBody(), true);
    }

    public function fetchNewsItem($id)
    {
        $response = $this->client->get("news/{$id}");
        return json_decode($response->getBody(), true);
    }

    public function updateNews($id, $title, $content)
    {
        $response = $this->client->put("news/{$id}", [
            'json' => ['title' => $title, 'content' => $content],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function deleteNews($id)
    {
        $response = $this->client->delete("news/{$id}");
        return json_decode($response->getBody(), true);
    }
}
