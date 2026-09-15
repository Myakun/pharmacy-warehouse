<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;
use app\models\Shipment;
use yii\helpers\Html;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\shipment\Index $filterModel
 */

$this->title = Yii::t('app', 'Shipments');

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
    'toolbar' => Yii::$app->getUser()->can(Shipment::PERMISSION_MANAGE) ? [
        'content' => Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success'])
    ] : false,
]);