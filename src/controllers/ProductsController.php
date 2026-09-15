<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\Product;
use app\models\product\Index;
use app\models\product\Save;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProductsController extends Controller
{
    use CRUDTrait;

    public function actionAutocomplete(string $query = '', bool $useId = false): array
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $result = ['results' => []];

        if ('' == $query) {
            return $result;
        }

        $query = Product::find()
            ->select(['id', 'name'])
            ->andWhere(['like', 'lower(name)', mb_strtolower(trim($query))])
            ->limit(10)
            ->asArray();

        foreach ($query->all() as $row) {
            $result['results'][] = [
                'id' => $useId ? $row['id'] : $row['name'],
                'text' => $row['name'],
            ];
        }

        return $result;
    }

    public function actionCreate(): Response
    {
        return $this->create(new Save(new Product()), [
            'successMessage' => Yii::t('app', 'Product created'),
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $product = Product::findOne($id);
        if (null == $product) {
            throw new NotFoundHttpException();
        }

        if (!empty($product->receiptsProducts)) {
            throw new ForbiddenHttpException();
        }

        return $this->delete($product, [
            'successMessage' => Yii::t('app', 'Product deleted'),
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
        $product = Product::findOne($id);
        if (null == $product) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(Product::findOne($id)), [
            'successMessage' => Yii::t('app', 'Product updated'),
        ]);
    }
}
