<?php

declare(strict_types=1);

namespace app\components\web;

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class Controller extends \yii\web\Controller
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }
}
