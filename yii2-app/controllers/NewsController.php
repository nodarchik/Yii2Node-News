<?php

namespace app\controllers;

use app\services\NewsService;
use Exception;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class NewsController extends Controller
{
    private $_newsService;

    public function __construct($id, Module $module, NewsService $newsService, $config = [])
    {
        $this->_newsService = $newsService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function actionIndex(): string
    {
        $token = Yii::$app->session->get('user.token');
        $news = $this->_newsService->getAllNews($token);
        return $this->render('index', ['news' => $news]);
    }

    /**
     * @throws Exception
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $token = Yii::$app->session->get('user.token');
            $data = $request->post();
            $this->_newsService->createNews($data, $token);
            return $this->redirect(['index']);
        }

        return $this->render('create');
    }

    /**
     * @throws Exception
     */
    public function actionUpdate($id): string
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $token = Yii::$app->session->get('user.token');
            $data = $request->post();
            $this->_newsService->updateNews($id, $data, $token);
            return $this->redirect(['index']);
        }

        $token = Yii::$app->session->get('user.token');
        $news = $this->_newsService->getNewsById($id, $token);
        return $this->render('update', ['news' => $news]);
    }

    /**
     * @throws Exception
     */
    public function actionDelete($id): Response
    {
        $token = Yii::$app->session->get('user.token');
        $this->_newsService->deleteNews($id, $token);
        return $this->redirect(['index']);
    }
}
