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
    'month' => [
        'attribute' => 'month',
        'group' => true,
        'label' => Yii::t('app', 'Month'),
    ],
];