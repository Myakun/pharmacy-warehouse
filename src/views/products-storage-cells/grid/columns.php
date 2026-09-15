<?php

declare(strict_types=1);

use app\components\widgets\grid\ActionColumn;
use app\models\Product;
use yii\helpers\Html;

return [
    'productName' => [
        'attribute' => 'productName',
        'label' => Yii::t('app', 'Product'),
    ],
    'series' => [
        'attribute' => 'series',
        'header' => Yii::t('app', 'Batch'),
    ],
    'amount' => [
        'attribute' => 'amount',
        'label' => Yii::t('app', 'Quantity'),
    ],
    'productionDate' => [
        'attribute' => 'productionDate',
        'label' => Yii::t('app', 'Production date'),
    ],
    'expirationDate' => [
        'attribute' => 'expirationDate',
        'label' => Yii::t('app', 'Expiration date'),
    ],
    'expirationPercentage' => [
        'attribute' => 'expirationPercentage',
        'label' => Yii::t('app', 'Shelf life left (%)'),
        'value' => function (array $model) {
            return $model['expirationPercentage'] . '%';
        },
    ],
    'storageCells' => [
        'format' => 'raw',
        'header' => Yii::t('app', 'Cells'),
        'value' => function (array $model) {
            $html = '';

            foreach ($model['storageCells'] as $storageCellName => $amount) {
                $html .= $storageCellName . ': ' . $amount . ' ' . Yii::t('app', 'pcs') . '<br>';
            }

            return $html;
        },
    ],
];