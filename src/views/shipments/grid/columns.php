<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;
use app\models\Customer;
use app\components\widgets\grid\ActionColumn;
use app\models\Shipment;
use yii\helpers\Html;
use yii\helpers\Url;

$labels = (new Shipment())->attributeLabels();

return [
    [
        'allowBatchToggle' => true,
        'class' => kartik\grid\ExpandRowColumn::class,
        'detailRowCssClass' => false,
        'detailUrl' => Url::toRoute(['products']),
        'enableCache' => false,
        'expandOneOnly' => true,
        'headerOptions' => [
            'class' => 'kartik-sheet-style',
            'width' => '50px'
        ],
        'value' => function () {
            return GridView::ROW_COLLAPSED;
        },
        'width' => '50px',
    ],
    'id' => [
        'format' => 'raw',
        'header' => '#',
        'value' => function(Shipment $shipment): string {
            return $this->render('@app/views/shipments/grid/id', [
                'shipment' => $shipment
            ]);
        }
    ],
    'invoice_number' => [
        'attribute' => 'invoice_number',
        'value' => function (Shipment $shipment) {
            return Shipment::INVOICE_NUMBER_PREFIX . $shipment->invoice_number;
        },
    ],
    'invoice_date' => [
        'attribute' => 'invoice_date',
        'label' => $labels['invoice_date'],
        'value' => function (Shipment $shipment) {
            return Yii::$app->formatter->asDate($shipment->invoice_date);
        },
    ],
    'customer' => [
        'attribute' => 'customer_id',
        'enableSorting' => false,
        'format' => 'raw',
        'value' => function(Shipment $shipment): string {
            return $this->render('@app/views/customers/grid/customer', [
                'customer' => $shipment->customer,
            ]);
        }
    ],
    [
        'buttons' => [
            'products' => function ($url, $model, $key): string {
                return Html::a(Yii::t('app', 'Products'), "/shipments-products/index?shipmentId=$key", [
                    'class' => 'btn btn-light btn-sm mb-3 mt-3',
                ]);
            }
        ],
        'class' => ActionColumn::class,
        'template' => '{update} {products} {delete}',
        'visibleButtons' => [
            'delete' => function (Shipment $shipment) {
                return empty($shipment->shipmentProducts) && Yii::$app->getUser()->can(Shipment::PERMISSION_MANAGE);
            },
            'products' => function ()  {
                return Yii::$app->getUser()->can(Shipment::PERMISSION_MANAGE);
            },
            'update' => function ()  {
                return Yii::$app->getUser()->can(Shipment::PERMISSION_MANAGE);
            },
        ],
    ]
];