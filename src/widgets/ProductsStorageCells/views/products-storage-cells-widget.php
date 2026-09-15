<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;
use app\models\Product;
use yii\helpers\Html;

/**
 * @var \yii\data\ArrayDataProvider $dataProvider
 * @var Product $product
 */

echo GridView::widget([
    'columns' => [
        'storageCell' => [
            'attribute' => 'storageCell',
            'header' => Yii::t('app', 'Cell'),
        ],
        'series' => [
            'attribute' => 'series',
            'header' => Yii::t('app', 'Batch'),
        ],

        'amount' => [
            'attribute' => 'amount',
            'format' => 'raw',
            'label' => Yii::t('app', 'Quantity'),
            'value' => function (array $model) {
                $options = [];
                for ($i = 0; $i <= $model['amount']; $i++) {
                    $options[$i] = $i;
                }

                $html = Html::dropDownList('amounts[' . $model['productStorageCellId'] . ']', 0, $options);
                $html .= '&nbsp; ' . Yii::t('app', 'of') . ' ' . $model['amount'];

                return $html;
            },
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
    ],
    'dataProvider' => $dataProvider,
    'panel' => [
        'after' => false,
        'heading' => $product->name,
    ],
    'rowOptions' => function(array $model) {
        if ($model['expirationPercentage'] <= 10) {
            return ['class' => 'table-danger'];
        }

        if ($model['expirationPercentage'] <= 20) {
            return ['class' => 'table-warning'];
        }
    },
    'toolbar' => false
]);