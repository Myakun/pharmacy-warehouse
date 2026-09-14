<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\storage_mode\Index;
use app\models\storage_mode\Save;
use app\models\StorageMode;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class StorageModesController extends Controller
{
    use CRUDTrait;

    public function actionCreate(): Response
    {
        return $this->create(new Save(new StorageMode()), [
            'successMessage' => 'Условие хранения успешно создано',
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $storageMode = StorageMode::findOne($id);
        if (null == $storageMode) {
            throw new NotFoundHttpException();
        }

        if (!empty($storageMode->products)) {
            throw new ForbiddenHttpException();
        }

        return $this->delete($storageMode, [
            'successMessage' => 'Условие хранения успешно удалено',
        ]);
    }

    public function actionIndex(): Response
    {
        $filterModel = new Index();
        $attributes = Yii::$app->getRequest()->get();
        $filterModel->load($attributes);

        return $this->index([
            'dataProvider' => [
                'query' => $filterModel->getQuery(),
            ],
            'viewParams' => [
                'filterModel' => $filterModel,
            ],
        ]);
    }

    public function actionUpdate(int $id): Response
    {
        $storageMode = StorageMode::findOne($id);
        if (null == $storageMode) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(StorageMode::findOne($id)), [
            'successMessage' => 'Условие хранения успешно изменено',
        ]);
    }
}
