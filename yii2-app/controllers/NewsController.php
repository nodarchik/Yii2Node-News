<?php

namespace app\controllers;

use app\services\ApiService;
use Yii;
use yii\web\Controller;
use app\models\NewsForm;

class NewsController extends Controller
{
    private $apiService;

    public function __construct($id, $module, ApiService $apiService, $config = [])
    {
        $this->apiService = $apiService;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $newsList = $this->apiService->fetchNews();
        return $this->render('index', [
            'newsList' => $newsList,
        ]);
    }

    public function actionCreate()
    {
        $model = new NewsForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $response = $this->apiService->createNews($model->title, $model->content);
            if ($response['success']) {  // Assuming 'success' is a field in the response
                Yii::$app->session->setFlash('success', 'News created successfully.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to create news.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = new NewsForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $response = $this->apiService->updateNews($id, $model->title, $model->content);
            if ($response['success']) {
                Yii::$app->session->setFlash('success', 'News updated successfully.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update news.');
            }
        } else {
            $newsData = $this->apiService->fetchNewsItem($id);
            if ($newsData) {
                $model->title = $newsData['title'];
                $model->content = $newsData['content'];
            } else {
                Yii::$app->session->setFlash('error', 'News not found.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $newsData = $this->apiService->fetchNewsItem($id);
        if ($newsData) {
            return $this->render('view', [
                'newsData' => $newsData,
            ]);
        } else {
            Yii::$app->session->setFlash('error', 'News not found.');
            return $this->redirect(['index']);
        }
    }

    public function actionDelete($id)
    {
        $response = $this->apiService->deleteNews($id);
        if ($response['success']) {
            Yii::$app->session->setFlash('success', 'News deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete news.');
        }
        return $this->redirect(['index']);
    }
}
