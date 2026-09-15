<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;
use app\models\Customer;
use app\components\widgets\grid\ActionColumn;
use app\models\Receipt;
use yii\helpers\Html;
use yii\helpers\Url;

$labels = (new Receipt())->attributeLabels();

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
        'value' => function(Receipt $receipt): string {
            return $this->render('@app/views/receipts/grid/id', [
                'receipt' => $receipt
            ]);
        }
    ],
    'invoice_number' => [
        'attribute' => 'invoice_number',
        'value' => function (Receipt $receipt) {
            return Receipt::INVOICE_NUMBER_PREFIX . $receipt->invoice_number;
        },
    ],
    'invoice_date' => [
        'attribute' => 'invoice_date',
        'label' => $labels['invoice_date'],
        'value' => function (Receipt $receipt) {
            return Yii::$app->formatter->asDate($receipt->invoice_date);
        },
    ],
    'supplier' => [
        'attribute' => 'supplier_id',
        'enableSorting' => false,
        'format' => 'raw',
        'value' => function(Receipt $receipt): string {
            return $this->render('@app/views/suppliers/grid/supplier', [
                'supplier' => $receipt->supplier,
            ]);
        }
    ],
    [
        'buttons' => [
            'products' => function ($url, $model, $key): string {
                return Html::a(Yii::t('app', 'Products'), "/receipts-products/index?receiptId=$key", [
                    'class' => 'btn btn-light btn-sm mb-3 mt-3',
                ]);
            }
        ],
        'class' => ActionColumn::class,
        'template' => '{update} {products} {delete}',
        'visibleButtons' => [
            'delete' => function (Receipt $receipt) {
                return empty($receipt->receiptProducts) && Yii::$app->getUser()->can(Receipt::PERMISSION_MANAGE);
            },
            'products' => function ()  {
                return Yii::$app->getUser()->can(Receipt::PERMISSION_MANAGE);
            },
            'update' => function ()  {
                return Yii::$app->getUser()->can(Receipt::PERMISSION_MANAGE);
            },
        ],
    ]
];