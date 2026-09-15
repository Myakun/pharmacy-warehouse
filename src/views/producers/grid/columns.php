<?php

declare(strict_types=1);

use app\models\Producer;
use app\components\widgets\grid\ActionColumn;
use yii\helpers\Html;

return [
    'id' => [
        'format' => 'raw',
        'header' => '#',
        'value' => function(Producer $producer) {
            return $this->render('grid/id', [
                'producer' => $producer
            ]);
        }
    ],
    'name' => [
        'attribute' => 'name',
    ],
    'products_count' => [
        'attribute' => 'products_count',
        'format' => 'raw',
        'header' => Yii::t('app', 'Products'),
        'value' => function(Producer $producer) {
            if (empty($producer->products)) {
                return 0;
            }

            return Html::a(count($producer->products), ['/products/index', 'Index[producer]' => $producer->name]);
        }
    ],
    [
        'class' => ActionColumn::class,
        'visibleButtons' => [
            'delete' => function (Producer $producer) {
                return count($producer->products) == 0 && Yii::$app->getUser()->can(Producer::PERMISSION_MANAGE);
            },
            'update' => function ()  {
                return Yii::$app->getUser()->can(Producer::PERMISSION_MANAGE);
            },
        ],
    ]
];