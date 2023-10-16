<?php

namespace app\services;

use app\clients\UserApiClient;
use app\models\User;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class UserService extends Component
{
    private $userApiClient;

    public function __construct(UserApiClient $userApiClient, $config = [])
    {
        $this->userApiClient = $userApiClient;
        parent::__construct($config);
    }

    /**
     * Registers a new user.
     *
     * @param array $data
     * @return User
     * @throws InvalidConfigException|GuzzleException if registration fails
     */
    public function registerUser(array $data): User
    {
        $apiResponse = $this->userApiClient->registerUser($data);

        if (isset($apiResponse['_id'])) {
            return new User([
                'id' => $apiResponse['_id'],
                'username' => $apiResponse['username'],
            ]);
        }

        throw new InvalidConfigException('User registration failed.');
    }

    /**
     * Retrieves all users.
     *
     * @param string $token
     * @return User[]
     * @throws InvalidConfigException|GuzzleException if retrieval fails
     */
    public function getAllUsers(string $token): array
    {
        $apiResponse = $this->userApiClient->getAllUsers($token);

        if (isset($apiResponse['error'])) {
            throw new InvalidConfigException('User retrieval failed: ' . $apiResponse['error']);
        }

        $users = [];
        foreach ($apiResponse as $userData) {
            $users[] = new User([
                'id' => $userData['_id'],
                'username' => $userData['username'],
            ]);
        }
        return $users;
    }

    /**
     * Updates a user.
     *
     * @param string $id
     * @param array $data
     * @param string $token
     * @return User
     * @throws InvalidConfigException|GuzzleException if update fails
     */
    public function updateUser(string $id, array $data, string $token): User
    {
        $apiResponse = $this->userApiClient->updateUser($id, $data, $token);

        if (isset($apiResponse['_id'])) {
            return new User([
                'id' => $apiResponse['_id'],
                'username' => $apiResponse['username'],
            ]);
        }

        throw new InvalidConfigException('User update failed.');
    }

    /**
     * Deletes a user.
     *
     * @param string $id
     * @param string $token
     * @return bool
     * @throws InvalidConfigException|GuzzleException if deletion fails
     */
    public function deleteUser(string $id, string $token): bool
    {
        $apiResponse = $this->userApiClient->deleteUser($id, $token);

        if (isset($apiResponse['success']) && $apiResponse['success'] === true) {
            return true;
        }

        throw new InvalidConfigException('User deletion failed.');
    }

    /**
     * Retrieves a user by ID.
     *
     * @param string $id
     * @param string $token
     * @return User|null
     * @throws InvalidConfigException if retrieval fails
     */
    public function getUserById(string $id, string $token): ?User
    {
        try {
            $apiResponse = $this->userApiClient->getUserById($id, $token);

            // Assumes the API response contains a user object if successful
            if (isset($apiResponse['_id'])) {
                return new User([
                    'id' => $apiResponse['_id'],
                    'username' => $apiResponse['username'],
                ]);
            }
        } catch (GuzzleException $e) {
            Yii::error("Failed to retrieve user: {$e->getMessage()}", __METHOD__);
            throw new InvalidConfigException('User retrieval failed.', 0, $e);
        } catch (\Exception $e) {
            Yii::error("Failed to retrieve user: {$e->getMessage()}", __METHOD__);
            throw new InvalidConfigException('User retrieval failed.', 0, $e);
        }

        return null;
    }

}
