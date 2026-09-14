<?php

declare(strict_types=1);

namespace app\controllers\statistics;

use app\components\web\Controller;
use app\models\Receipt;
use app\models\statistics\ReceiptsProductsFromSuppliers;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\web\Response;

class ReceiptsProductsFromSuppliersController extends Controller
{
    public function actionIndex(): Response
    {
        $filterModel = new ReceiptsProductsFromSuppliers();
        $attributes = Yii::$app->getRequest()->get();
        $filterModel->load($attributes);

        $allModels = [];
        foreach ($filterModel->getQuery()->all() as $receipt) {
            /**
             * @var Receipt $receipt
             */
            foreach ($receipt->receiptProducts as $receiptProduct) {
                $hash = sprintf(
                    '%s-%s-%s',
                    $receiptProduct->receipt_id,
                    $receiptProduct->product_id,
                    $receiptProduct->series
                );

                if (isset($allModels[$hash])) {
                    $model = $allModels[$hash];
                } else {
                    $model = [
                        'amount' => 0,
                        'expirationDate' => Yii::$app->formatter->asDate($receiptProduct->expiration_date),
                        'invoiceDate' => Yii::$app->formatter->asDate($receipt->invoice_date),
                        'invoiceNumber' => Receipt::INVOICE_NUMBER_PREFIX . $receipt->invoice_number,
                        'productName' => $receiptProduct->product->name,
                        'productionDate' => Yii::$app->formatter->asDate($receiptProduct->production_date),
                        'series' => $receiptProduct->series,
                        'supplier' => $receipt->supplier->name . ' (' . $receipt->supplier->address . ')',
                    ];
                }

                $model['amount'] += $receiptProduct->packages_amount;

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
