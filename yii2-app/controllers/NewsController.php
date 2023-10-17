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
        } catch (GuzzleException $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw new NotFoundHttpException('Failed to retrieve news.');
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw new NotFoundHttpException($e->getMessage());
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
            } catch (GuzzleException $e) {
                Yii::error($e->getMessage(), __METHOD__);
                throw new BadRequestHttpException('Failed to create news.');
            } catch (\Exception $e) {
                Yii::error($e->getMessage(), __METHOD__);
                throw new BadRequestHttpException($e->getMessage());
            }

            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $token = Yii::$app->session->get('user.token');
        if (!$token) {
            throw new NotFoundHttpException('Unauthorized access.');
        }

        try {
            $news = $this->_newsService->getNewsById($id, $token);
        } catch (GuzzleException $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw new NotFoundHttpException('Failed to retrieve news.');
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw new NotFoundHttpException($e->getMessage());
        }

        $model = new News();
        $model->attributes = $news->attributes;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $this->_newsService->updateNews($id, $model->attributes, $token);
                return $this->redirect(['index']);
            } catch (GuzzleException $e) {
                Yii::error($e->getMessage(), __METHOD__);
                Yii::$app->session->setFlash('error', 'Failed to update news.');
            } catch (\Exception $e) {
                Yii::error($e->getMessage(), __METHOD__);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
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
        } catch (GuzzleException $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw new NotFoundHttpException('Failed to delete news.');
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            throw new NotFoundHttpException($e->getMessage());
        }

        return $this->redirect(['index']);
    }
}
