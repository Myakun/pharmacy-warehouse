<?php

declare(strict_types=1);

use app\components\widgets\grid\ActionColumn;
use app\models\Product;
use yii\helpers\Html;

return [

    'productName' => [
        'attribute' => 'productName',
        'header' => Yii::t('app', 'Product'),
    ],
    'amount' => [
        'attribute' => 'amount',
        'label' => Yii::t('app', 'Quantity'),
    ],
    'supplier' => [
        'attribute' => 'supplier',
        'header' => Yii::t('app', 'Supplier'),
        'group' => true,
    ],
    'invoiceNumber' => [
        'attribute' => 'invoiceNumber',
        'header' => Yii::t('app', 'Invoice number'),
        'group' => true,
    ],
    'invoiceDate' => [
        'attribute' => 'invoiceDate',
        'label' => Yii::t('app', 'Invoice date'),
    ],
    'series' => [
        'attribute' => 'series',
        'header' => Yii::t('app', 'Batch'),
    ],
    'productionDate' => [
        'attribute' => 'productionDate',
        'label' => Yii::t('app', 'Production date'),
    ],
    'expirationDate' => [
        'attribute' => 'expirationDate',
        'label' => Yii::t('app', 'Expiration date'),
    ],
];