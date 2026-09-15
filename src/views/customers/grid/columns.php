<?php

declare(strict_types=1);

use app\models\Customer;
use app\components\widgets\grid\ActionColumn;


return [
    'id' => [
        'format' => 'raw',
        'header' => '#',
        'value' => function(Customer $customer): string {
            return $this->render('grid/id', [
                'customer' => $customer
            ]);
        }
    ],
    'name' => [
        'attribute' => 'name',
        'format' => 'raw',
        'label' => Yii::t('app', 'Customer'),
        'value' => function(Customer $customer): string {
            return $this->render('grid/customer', [
                'customer' => $customer
            ]);
        }
    ],
    'contract' => [
        'attribute' => 'contractNumber',
        'format' => 'raw',
        'header' => Yii::t('app', 'Contract'),
        'value' => function(Customer $customer): string {
            return sprintf(
                Yii::t('app', '%s dated %s'),
                Customer::CONTRACT_NUMBER_PREFIX . $customer->contract_number,
                Yii::$app->formatter->asDate($customer->contract_date)
            );
        }
    ],
    [
        'class' => ActionColumn::class,
        'template' => '{update} {delete}',
        'visibleButtons' => [
            'delete' => function (Customer $customer)  {
                return empty($customer->shipments) && Yii::$app->getUser()->can(Customer::PERMISSION_MANAGE);
            },
            'update' => function ()  {
                return Yii::$app->getUser()->can(Customer::PERMISSION_MANAGE);
            },
        ]
    ]
];