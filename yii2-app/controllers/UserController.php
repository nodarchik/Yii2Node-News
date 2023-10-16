<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\User;
use app\services\UserService;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends Controller
{
    private $userService;

    public function __construct($id, $module, UserService $userService, $config = [])
    {
        $this->userService = $userService;
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
            } catch (GuzzleException|InvalidConfigException $e) {
            }

            return $this->redirect(['view', 'id' => $user->id]);
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
        } catch (GuzzleException|InvalidConfigException $e) {
            Yii::error($e->getMessage(), __METHOD__);
            // Handle exception
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
