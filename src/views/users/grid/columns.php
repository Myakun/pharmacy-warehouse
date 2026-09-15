<?php

declare(strict_types=1);

use app\models\User;
use app\components\widgets\grid\ActionColumn;

$authManager = Yii::$app->getAuthManager();
$generalDirectorId = $authManager->getUserIdsByRole(User::ROLE_GENERAL_DIRECTOR)[0];

return [

    'id' => [
        'format' => 'raw',
        'header' => '#',
        'value' => function(User $user) {
            return $this->render('grid/id', [
                'user' => $user
            ]);
        }
    ],
    'name',
    'role' => [
        'format' => 'raw',
        'header' => Yii::t('app', 'Role'),
        'value' => function (User $user) {
            $result = '';

            $authManager = Yii::$app->getAuthManager();
            foreach ($authManager->getRolesByUser($user->id) as $role) {
                $result .= $role->description . '<br>';
            }

            return $result;
        },
    ],
    [
        'class' => ActionColumn::class,
        'visibleButtons' => [
            'delete' => function (User $user) use ($generalDirectorId) {
                if ($user->id == $generalDirectorId) {
                    return false;
                }

                return $user->id != Yii::$app->getUser()->getId();
            },
            'update' => function (User $user) use ($generalDirectorId) {
                if ($user->id != $generalDirectorId) {
                    return true;
                }

                return Yii::$app->getUser()->getId() == $generalDirectorId;
            },
        ]
    ]
];