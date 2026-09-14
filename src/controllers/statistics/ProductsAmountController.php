<?php

declare(strict_types=1);

namespace app\controllers\statistics;

use app\components\web\Controller;
use app\models\Shipment;
use app\models\statistics\ProductsAmount;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\web\Response;

class ProductsAmountController extends Controller
{
    public function actionIndex(): Response
    {
        $filterModel = new ProductsAmount();
        $attributes = Yii::$app->getRequest()->get();
        $filterModel->load($attributes);

        $allModels = [];
        foreach ($filterModel->getQuery()->all() as $shipment) {
            /**
             * @var Shipment $shipment
             */
            foreach ($shipment->shipmentProducts as $shipmentProduct) {
                $productStorageCell = $shipmentProduct->productStorageCell;

                $month = date('m Y', strtotime($shipment->invoice_date));

                $hash = sprintf(
                    '%s-%s',
                    $productStorageCell->receiptProduct->product_id,
                    $month
                );

                if (isset($allModels[$hash])) {
                    $model = $allModels[$hash];
                } else {
                    $model = [
                        'amount' => 0,
                        'month' => $month,
                        'productName' => $productStorageCell->receiptProduct->product->name,
                    ];
                }

                $model['amount'] += $shipmentProduct->packages_amount;

                $allModels[$hash] = $model;
            }
        }

        return new Response([
            'data' => $this->render('index', [
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $allModels,
                    'pagination' => false,
                    'sort' => new Sort([
                        'attributes' => [
                            'amount',
                            'month',
                        ],
                    ]),
                ]),
                'filterModel' => $filterModel,
            ]),
        ]);
    }
}
