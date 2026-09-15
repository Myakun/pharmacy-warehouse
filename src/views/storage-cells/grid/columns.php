<?php

declare(strict_types=1);

use app\models\StorageCell;
use app\components\widgets\grid\ActionColumn;
use kartik\helpers\Html;

$labels = (new StorageCell())->attributeLabels();

return [
    'id' => [
        'format' => 'raw',
        'header' => '#',
        'value' => function(StorageCell $storageCell): string {
            return $this->render('grid/id', [
                'storageCell' => $storageCell
            ]);
        }
    ],
    'name' => [
        'attribute' => 'name',
        'label' => Yii::t('app', 'Cell'),
        'value' => function(StorageCell $storageCell): string {
            return $storageCell->getName();
        }
    ],
    'storage_mode_id' => [
        'format' => 'raw',
        'header' => $labels['storage_mode_id'],
        'value' => function (StorageCell $storageCell): string {
            return $storageCell->storageMode->name;
        },
    ],
    'volume',
    'volume_left',
    [
        'class' => ActionColumn::class,
        'visibleButtons' => [
            'delete' => function (StorageCell $storageCell) {
                return count($storageCell->productsStorageCells) == 0 && Yii::$app->getUser()->can(StorageCell::PERMISSION_MANAGE);
            },
            'update' => function ()  {
                return Yii::$app->getUser()->can(StorageCell::PERMISSION_MANAGE);
            },
        ],
    ]
];