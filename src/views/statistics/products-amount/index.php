<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\statistics\ProductsAmount $filterModel
 */

$this->title = Yii::t('app', 'Shipped quantities');

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
    'toolbar' => false
]);