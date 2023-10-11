<?php

namespace app\controllers;

use app\services\ApiService;
use Yii;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\SignupForm;

class SiteController extends Controller
{
    private $apiService;

    public function __construct($id, $module, ApiService $apiService, $config = [])
    {
        $this->apiService = $apiService;
        parent::__construct($id, $module, $config);
    }

    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $response = $this->apiService->loginUser($model->username, $model->password);
            if (isset($response['token'])) {
                // Store token in session or wherever you prefer
                Yii::$app->session->set('user.token', $response['token']);
                return $this->redirect(['news/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Login failed.');
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $response = $this->apiService->registerUser($model->username, $model->password, $model->confirmPassword);
            if (isset($response['token'])) {
                Yii::$app->session->set('user.token', $response['token']);
                return $this->redirect(['news/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Signup failed.');
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
}
