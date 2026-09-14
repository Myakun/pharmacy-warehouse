<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\Supplier;
use app\models\supplier\Index;
use app\models\supplier\Save;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SuppliersController extends Controller
{
    use CRUDTrait;

    public function actionAutocomplete(string $query = ''): array
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $result = ['results' => []];

        if ('' == $query) {
            return $result;
        }

        $query = Supplier::find()
            ->select(['address', 'id', 'name'])
            ->andWhere(['like', 'lower(name)', mb_strtolower(trim($query))])
            ->limit(10)
            ->asArray();

        foreach ($query->all() as $row) {
            $result['results'][] = [
                'id' => $row['id'],
                'text' => $row['name'] . ' (' . $row['address'] . ')',
            ];
        }

        return $result;
    }

    public function actionCreate(): Response
    {
        return $this->create(new Save(new Supplier()), [
            'successMessage' => 'Поставщик успешно создан',
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $supplier = Supplier::findOne($id);
        if (null == $supplier) {
            throw new NotFoundHttpException();
        }

        if (!empty($supplier->receipts)) {
            throw new ForbiddenHttpException();
        }

        return $this->delete($supplier, [
            'successMessage' => 'Поставщик успешно удален',
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
        $supplier = Supplier::findOne($id);
        if (null == $supplier) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(Supplier::findOne($id)), [
            'successMessage' => 'Поставщик успешно изменён',
        ]);
    }
}
