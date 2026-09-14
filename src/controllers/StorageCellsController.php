<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\storage_cell\Index;
use app\models\storage_cell\Save;
use app\models\StorageCell;
use Yii;
use yii\data\Sort;
use yii\db\Expression;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class StorageCellsController extends Controller
{
    use CRUDTrait;

    public function actionCreate(): Response
    {
        return $this->create(new Save(new StorageCell()), [
            'successMessage' => 'Ячейка скалада успешно создана',
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $storageCell = StorageCell::findOne($id);
        if (null == $storageCell) {
            throw new NotFoundHttpException();
        }

        if (!empty($storageCell->productsStorageCells)) {
            throw new ForbiddenHttpException();
        }

        return $this->delete($storageCell, [
            'successMessage' => 'Ячейка скалада успешно удалена',
        ]);
    }

    public function actionIndex(): Response
    {
        $filterModel = new Index();
        $attributes = Yii::$app->getRequest()->get();
        $filterModel->load($attributes);

        return $this->index([
            'dataProvider' => [
                'sort' => new Sort([
                    'attributes' => [
                        'name' => [
                            'asc' => [
                                new Expression('storage_mode_id ASC, row_number ASC, rack_number ASC, shelf_number ASC'),
                            ],
                            'desc' => [
                                new Expression('storage_mode_id DESC, row_number DESC, rack_number DESC, shelf_number DESC'),
                            ],
                        ],
                        'volume',
                        'volume_left',
                    ],
                ]),
                'query' => $filterModel->getQuery(),
            ],
            'viewParams' => [
                'filterModel' => $filterModel,
            ],
        ]);
    }

    public function actionUpdate(int $id): Response
    {
        $storageCell = StorageCell::findOne($id);
        if (null == $storageCell) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(StorageCell::findOne($id)), [
            'successMessage' => 'Ячейка скалада успешно изменена',
        ]);
    }
}
