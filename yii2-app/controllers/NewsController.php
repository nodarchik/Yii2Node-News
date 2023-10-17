<?php

namespace app\controllers;

use app\models\News;
use app\services\NewsService;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
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
     * @throws NotFoundHttpException
     */
    public function actionIndex(): string
    {
        $token = Yii::$app->session->get('user.token');
        if (!$token) {
            throw new NotFoundHttpException('Unauthorized access.');
        }
        try {
            $news = $this->_newsService->getAllNews($token);
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (GuzzleException $e) {
        }

        return $this->render('index', ['news' => $news]);
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionCreate()
    {
        $model = new News();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $token = Yii::$app->session->get('user.token');
            if (!$token) {
                throw new BadRequestHttpException('Unauthorized access.');
            }
            try {
                $this->_newsService->createNews($model->attributes, $token);
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            } catch (GuzzleException $e) {
            }

            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * @throws NotFoundHttpException|BadRequestHttpException
     */
    public function actionUpdate($id): string
    {
        $token = Yii::$app->session->get('user.token');
        if (!$token) {
            throw new NotFoundHttpException('Unauthorized access.');
        }
        try {
            $news = $this->_newsService->getNewsById($id, $token);
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (GuzzleException $e) {
        }

        $model = new News();
        $model->attributes = $news->attributes;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $this->_newsService->updateNews($id, $model->attributes, $token);
            } catch (\Exception $e) {
                throw new BadRequestHttpException($e->getMessage());
            } catch (GuzzleException $e) {
            }

            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDelete($id): Response
    {
        $token = Yii::$app->session->get('user.token');
        if (!$token) {
            throw new NotFoundHttpException('Unauthorized access.');
        }
        try {
            $this->_newsService->deleteNews($id, $token);
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (GuzzleException $e) {
        }

        return $this->redirect(['index']);
    }
}
