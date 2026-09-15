<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;
use yii\helpers\Html;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\product_storage_cell\Index $filterModel
 */

$this->title = Yii::t('app', 'Stock');

echo GridView::widget([
    'columns' => include(__DIR__ . '/grid/columns.php'),
    'dataProvider' => $dataProvider,
    'panel' => [
        'after' => false,
        'before' => $this->render('index/filter', [
            'model' => $filterModel
        ]),
        'heading' => $this->title,
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