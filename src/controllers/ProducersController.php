<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\Producer;
use app\models\producer\Index;
use app\models\producer\Save;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProducersController extends Controller
{
    use CRUDTrait;

    public function actionAutocomplete(string $query = ''): array
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $result = ['results' => []];

        if ('' == $query) {
            return $result;
        }

        $query = Producer::find()
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
        return $this->create(new Save(new Producer()), [
            'successMessage' => 'Производитель успешно создан',
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $producer = Producer::findOne($id);
        if (null == $producer) {
            throw new NotFoundHttpException();
        }

        if (!empty($producer->products)) {
            throw new ForbiddenHttpException();
        }

        return $this->delete($producer, [
            'successMessage' => 'Производитель успешно удален',
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
        $producer = Producer::findOne($id);
        if (null == $producer) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(Producer::findOne($id)), [
            'successMessage' => 'Производитель успешно изменён',
        ]);
    }
}
