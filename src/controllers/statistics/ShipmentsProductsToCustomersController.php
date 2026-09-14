<?php

declare(strict_types=1);

namespace app\controllers\statistics;

use app\components\web\Controller;
use app\models\Shipment;
use app\models\statistics\ShipmentsProductsToCustomers;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\web\Response;

class ShipmentsProductsToCustomersController extends Controller
{
    public function actionIndex(): Response
    {
        $filterModel = new ShipmentsProductsToCustomers();
        $attributes = Yii::$app->getRequest()->get();
        $filterModel->load($attributes);

        $allModels = [];
        foreach ($filterModel->getQuery()->all() as $shipment) {
            /**
             * @var Shipment $shipment
             */
            foreach ($shipment->shipmentProducts as $shipmentProduct) {
                $productStorageCell = $shipmentProduct->productStorageCell;

                $hash = sprintf(
                    '%s-%s-%s',
                    $shipmentProduct->shipment_id,
                    $productStorageCell->receiptProduct->product_id,
                    $productStorageCell->receiptProduct->series
                );

                if (isset($allModels[$hash])) {
                    $model = $allModels[$hash];
                } else {
                    $model = [
                        'amount' => 0,
                        'customer' => $shipment->customer->name . ' (' . $shipment->customer->address . ')',
                        'expirationDate' => Yii::$app->formatter->asDate($productStorageCell->receiptProduct->expiration_date),
                        'invoiceDate' => Yii::$app->formatter->asDate($shipment->invoice_date),
                        'invoiceNumber' => Shipment::INVOICE_NUMBER_PREFIX . $shipment->invoice_number,
                        'productName' => $productStorageCell->receiptProduct->product->name,
                        'productionDate' => Yii::$app->formatter->asDate($productStorageCell->receiptProduct->production_date),
                        'series' => $productStorageCell->receiptProduct->series,
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
                            'invoiceDate',
                        ],
                    ]),
                ]),
                'filterModel' => $filterModel,
            ]),
        ]);
    }
}
