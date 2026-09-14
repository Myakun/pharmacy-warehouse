<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SeriesController extends Controller
{
    use CRUDTrait;

    public function actionAutocomplete(string $query = ''): array
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $result = ['results' => []];

        if ('' == $query) {
            return $result;
        }

        $query = Series::find()
            ->select(['name'])
            ->andWhere(['like', 'lower(name)', mb_strtolower(trim($query))])
            ->limit(10)
            ->asArray();

        foreach ($query->all() as $row) {
            $result['results'][] = [
                'id' => $row['name'],
                'text' => $row['name'],
            ];
        }

        return $result;
    }

    public function actionCreate(): Response
    {
        return $this->create(new Save(new Series()), [
            'successMessage' => 'Серия товаров успешно создана',
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $series = Series::findOne($id);
        if (null == $series) {
            throw new NotFoundHttpException();
        }

        return $this->delete($series, [
            'successMessage' => 'Серия товаров успешно удалена',
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
        $series = Series::findOne($id);
        if (null == $series) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(Series::findOne($id)), [
            'successMessage' => 'Серия товаров успешно изменена',
        ]);
    }
}
