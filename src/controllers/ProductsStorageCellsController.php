<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\product_storage_cell\Index;
use app\models\ProductStorageCell;
use app\models\ReceiptProduct;
use DateTimeImmutable;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\db\ActiveQuery;
use yii\web\Response;

class ProductsStorageCellsController extends Controller
{
    use CRUDTrait;

    public function actionAutocompleteSeries(string $query = ''): array
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $result = ['results' => []];

        if ('' == $query) {
            return $result;
        }

        $query = ReceiptProduct::find()
            ->select(['series'])
            ->andWhere(['like', 'lower(series)', mb_strtolower(trim($query))])
            ->innerJoinWith(['productsStorageCells' => function (ActiveQuery $query) {
                $query->andWhere('amount > 0');
            }])
            ->limit(10)
            ->groupBy('series')
            ->asArray();

        foreach ($query->all() as $row) {
            $result['results'][] = [
                'id' => $row['series'],
                'text' => $row['series'],
            ];
        }

        return $result;
    }

    public function actionIndex(): Response
    {
        $filterModel = new Index();
        $attributes = Yii::$app->getRequest()->get();
        $filterModel->load($attributes);

        $allModels = [];
        foreach ($filterModel->getQuery()->all() as $productStorageCell) {
            /**
             * @var ProductStorageCell $productStorageCell
             */

            $hash = sprintf(
                '%s-%s',
                $productStorageCell->receiptProduct->product_id,
                $productStorageCell->receiptProduct->series
            );

            if (isset($allModels[$hash])) {
                $model = $allModels[$hash];
            } else {
                $expirationDate = $productStorageCell->receiptProduct->expiration_date;
                $productionDate = $productStorageCell->receiptProduct->production_date;

                $expirationDateObj = new DateTimeImmutable($expirationDate);
                $productionDateObj = new DateTimeImmutable($productionDate);
                $today = new DateTimeImmutable();

                if ($today >= $expirationDateObj) {
                    $expirationPercentage = 0;
                } else {
                    $expirationPercentage = (int) (100 * ($expirationDateObj->diff($today)->days + 1) / $expirationDateObj->diff($productionDateObj)->days);
                }

                $model = [
                    'amount' => 0,
                    'expirationDate' => Yii::$app->formatter->asDate($expirationDate),
                    'expirationPercentage' => $expirationPercentage,
                    'productName' => $productStorageCell->receiptProduct->product->name,
                    'productionDate' => Yii::$app->formatter->asDate($productionDate),
                    'series' => $productStorageCell->receiptProduct->series,
                    'storageCells' => [],
                ];
            }

            $model['amount'] += $productStorageCell->amount;
            $model['storageCells'][$productStorageCell->storageCell->getName()] = $productStorageCell->amount;
            $allModels[$hash] = $model;
        }

        return new Response([
            'data' => $this->render('index', [
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $allModels,
                    'pagination' => false,
                    'sort' => new Sort([
                        'attributes' => [
                            'amount',
                            'expirationDate',
                            'expirationPercentage',
                            'productName',
                            'productionDate',
                        ],
                    ]),
                ]),
                'filterModel' => $filterModel,
            ]),
        ]);
    }
}
