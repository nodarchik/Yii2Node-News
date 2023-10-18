<?php

namespace tests\unit\services;

use app\clients\UserApiClient;
use app\models\User;
use app\services\UserService;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use yii\base\InvalidConfigException;

class UserServiceTest extends TestCase
{
    private $userApiClientProphecy;
    private $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userApiClientProphecy = $this->prophesize(UserApiClient::class);
        $this->userService = new UserService($this->userApiClientProphecy->reveal());
    }

    /**
     * @dataProvider registrationDataProvider
     */
    /**
     * @dataProvider registrationDataProvider
     */
    public function testRegisterUser(array $userData, array $apiResponse, ?string $expectedException, ?string $expectedExceptionMessage): void
    {
        $this->userApiClientProphecy
            ->registerUser($userData)
            ->willReturn($apiResponse);

        if ($expectedException) {
            $this->expectException($expectedException);
            if ($expectedExceptionMessage) {
                $this->expectExceptionMessage($expectedExceptionMessage);
            }
        }

        $user = $this->userService->registerUser($userData);

        if (!$expectedException) {
            $this->assertInstanceOf(User::class, $user);
            $this->assertEquals($apiResponse['_id'], $user->id);
            $this->assertEquals($apiResponse['username'], $user->username);
        }
    }

    public function registrationDataProvider(): array
    {
        return [
            'successful registration' => [
                ['username' => 'testuser', 'password' => 'testpass'],
                ['_id' => '123', 'username' => 'testuser'],
                null,
                null,
            ],
            'failed registration' => [
                ['username' => 'testuser', 'password' => 'testpass'],
                [],
                InvalidConfigException::class,
                'User registration failed.',
            ],
        ];
    }


    public function testGetAllUsers(): void
    {
        $token = 'some-token';
        $apiResponse = [
            ['_id' => '1', 'username' => 'user1'],
            ['_id' => '2', 'username' => 'user2'],
        ];

        $this->userApiClientProphecy
            ->getAllUsers($token)
            ->willReturn($apiResponse);

        $users = $this->userService->getAllUsers($token);

        $this->assertCount(2, $users);
        $this->assertInstanceOf(User::class, $users[0]);
        $this->assertEquals('1', $users[0]->id);
        $this->assertEquals('user1', $users[0]->username);
    }

    public function testUpdateUser(): void
    {
        $userId = '1';
        $token = 'some-token';
        $userData = ['username' => 'updatedUser'];
        $apiResponse = ['_id' => '1', 'username' => 'updatedUser'];

        $this->userApiClientProphecy
            ->updateUser($userId, $userData, $token)
            ->willReturn($apiResponse);

        $user = $this->userService->updateUser($userId, $userData, $token);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($apiResponse['_id'], $user->id);
        $this->assertEquals($apiResponse['username'], $user->username);
    }

    public function testDeleteUser(): void
    {
        $userId = '1';
        $token = 'some-token';
        $apiResponse = ['success' => true];

        $this->userApiClientProphecy
            ->deleteUser($userId, $token)
            ->willReturn($apiResponse);

        $result = $this->userService->deleteUser($userId, $token);

        $this->assertTrue($result);
    }

    public function testGetUserById(): void
    {
        $userId = '1';
        $token = 'some-token';
        $apiResponse = ['_id' => '1', 'username' => 'user1'];

        $this->userApiClientProphecy
            ->getUserById($userId, $token)
            ->willReturn($apiResponse);

        $user = $this->userService->getUserById($userId, $token);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($apiResponse['_id'], $user->id);
        $this->assertEquals($apiResponse['username'], $user->username);
    }
}
