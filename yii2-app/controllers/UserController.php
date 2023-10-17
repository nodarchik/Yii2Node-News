<?php

declare(strict_types=1);

namespace app\controllers;

use app\clients\AuthApiClient;
use app\models\User;
use app\services\UserService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends Controller
{
    private $userService;
    private $authApiClient;

    public function __construct($id, $module, UserService $userService,AuthApiClient $authApiClient, $config = [])
    {
        $this->userService = $userService;
        $this->authApiClient = $authApiClient;
        parent::__construct($id, $module, $config);
    }

    private function getToken(): ?string
    {
        return Yii::$app->session->get('user.token');
    }

    /**
     * Lists all users.
     *
     * @return string
     * @throws NotFoundHttpException if token is not found
     */
    public function actionIndex(): string
    {
        $token = $this->getToken();

        if ($token === null) {
            throw new NotFoundHttpException('Authentication required.');
        }

        try {
            $users = $this->userService->getAllUsers($token);
        } catch (GuzzleException|InvalidConfigException $e) {
            Yii::error("Failed to get all users: {$e->getMessage()}", __METHOD__);
            throw new NotFoundHttpException('Unable to fetch user list.');
        }

        return $this->render('index', [
            'users' => $users,
        ]);
    }

    /**
     * Displays a single user.
     *
     * @param string $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(string $id): string
    {
        $token = $this->getToken();

        if ($token === null) {
            throw new NotFoundHttpException('Authentication required.');
        }

        try {
            $user = $this->userService->getUserById($id, $token);
        } catch (GuzzleException|InvalidConfigException $e) {
            Yii::error("Failed to get user by id: {$e->getMessage()}", __METHOD__);
            throw new NotFoundHttpException('The requested user does not exist.');
        }

        if ($user === null) {
            throw new NotFoundHttpException('The requested user does not exist.');
        }

        return $this->render('view', [
            'user' => $user,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            try {
                $response = $this->authApiClient->login($model->username, $model->password);
                if (isset($response['token'])) {
                    Yii::$app->session->set('user.token', $response['token']);
                    return $this->goBack();
                } else {
                    Yii::$app->session->setFlash('error', 'Login failed.');
                }
            } catch (Exception $e) {
                Yii::error($e->getMessage(), __METHOD__);
                Yii::$app->session->setFlash('error', 'An error occurred while trying to log in.');
            } catch (GuzzleException $e) {
                Yii::error($e->getMessage(), __METHOD__);
                Yii::$app->session->setFlash('error', 'An error occurred while trying to communicate with the authentication service.');
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new user.
     *
     * @return string|Response
     * @throws NotFoundHttpException if token is not found
     */
    public function actionCreate()
    {
        $token = $this->getToken();

        if ($token === null) {
            throw new NotFoundHttpException('Authentication required.');
        }

        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $user = $this->userService->registerUser($model->attributes);
                return $this->redirect(['view', 'id' => $user->id]);
            } catch (GuzzleException|InvalidConfigException $e) {
                Yii::error($e->getMessage(), __METHOD__);
                Yii::$app->session->setFlash('error', 'An error occurred while trying to register the user.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing user.
     *
     * @param string $id
     * @return string|Response
     * @throws NotFoundHttpException if token is not found
     */
    public function actionUpdate(string $id)
    {
        $token = $this->getToken();

        if ($token === null) {
            throw new NotFoundHttpException('Authentication required.');
        }

        $user = null;
        $updatedUser = null;

        try {
            $user = $this->userService->getUserById($id, $token);
        } catch (InvalidConfigException $e) {
            Yii::error($e->getMessage(), __METHOD__);
            Yii::$app->session->setFlash('error', 'An error occurred while trying to retrieve the user details.');
            return $this->redirect(['index']);
        }

        if ($user === null) {
            throw new NotFoundHttpException('The requested user does not exist.');
        }

        $model = new User();
        $model->attributes = $user->attributes;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $updatedUser = $this->userService->updateUser($id, $model->attributes, $token);
            } catch (GuzzleException|InvalidConfigException $e) {
                Yii::error($e->getMessage(), __METHOD__);
            }
        }

        if ($updatedUser !== null) {
            return $this->redirect(['view', 'id' => $updatedUser->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing user.
     *
     * @param string $id
     * @return Response
     * @throws NotFoundHttpException if token is not found
     */
    public function actionDelete(string $id): Response
    {
        $token = $this->getToken();

        if ($token === null) {
            throw new NotFoundHttpException('Authentication required.');
        }

        try {
            $this->userService->deleteUser($id, $token);
        } catch (GuzzleException|InvalidConfigException $e) {
            Yii::error("Failed to delete user: {$e->getMessage()}", __METHOD__);
            throw new NotFoundHttpException('Unable to delete user.');
        }

        return $this->redirect(['index']);
    }
}
