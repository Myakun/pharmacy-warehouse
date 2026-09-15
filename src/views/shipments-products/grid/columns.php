<?php

declare(strict_types=1);

use app\components\widgets\grid\ActionColumn;
use app\components\widgets\grid\GridView;
use app\models\ReceiptProduct;
use app\models\ShipmentProduct;
use yii\helpers\Url;

$labels = (new ShipmentProduct())->attributeLabels();
$receiptProductLabels = (new ReceiptProduct())->attributeLabels();

$columns = [
    'id' => [
        'format' => 'raw',
        'header' => '#',
        'value' => function(ShipmentProduct $shipmentProduct){
            return $this->render('@app/views/shipments-products/grid/id', [
                'shipmentProduct' => $shipmentProduct
            ]);
        }
    ],
    'product_id' => [
        'format' => 'raw',
        'header' => Yii::t('app', 'Product'),
        'value' => function(ShipmentProduct $shipmentProduct) {
            return $shipmentProduct->productStorageCell->receiptProduct->product->name;
        }
    ],
    'series' => [
        'header' => $receiptProductLabels['series'],
        'value' => function (ShipmentProduct $shipmentProduct) {
            return $shipmentProduct->productStorageCell->receiptProduct->series;
        },
    ],
    'production_date' => [
        'header' => $receiptProductLabels['production_date'],
        'value' => function (ShipmentProduct $shipmentProduct) {
            return Yii::$app->formatter->asDate($shipmentProduct->productStorageCell->receiptProduct->production_date);
        },
    ],
    'expiration_date' => [
        'header' => $receiptProductLabels['expiration_date'],
        'value' => function (ShipmentProduct $shipmentProduct) {
            return Yii::$app->formatter->asDate($shipmentProduct->productStorageCell->receiptProduct->expiration_date);
        },
    ],
    'packages_amount' => [
        'attribute' => 'packages_amount',
        'enableSorting' => false,
    ],
    'storage_cell' => [
        'header' => Yii::t('app', 'Storage cell'),
        'value' => function (ShipmentProduct $shipmentProduct) {
            return $shipmentProduct->productStorageCell->storageCell->getName();
        },
    ],
];

if (!isset($disableDelete)) {
    $columns[] =   [
        'class' => ActionColumn::class,
        'template' => '{delete}'
    ];
}

return $columns;