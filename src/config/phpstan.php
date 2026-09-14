<?php

declare(strict_types=1);

/**
 * Application config for static analysis only.
 * Consumed by yii2-extensions/phpstan to resolve the types of Yii::$app components
 * and params.
 */

use app\models\User;
use himiklab\yii2\recaptcha\ReCaptchaConfig;
use yii\caching\FileCache;
use yii\db\Connection;
use yii\rbac\DbManager;

return [
    'params' => [
        'bsVersion' => '5.x',
    ],
    'components' => [
        'authManager' => [
            'class' => DbManager::class,
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'db' => [
            'class' => Connection::class,
            'dsn' => 'sqlite::memory:',
        ],
        'reCaptcha' => [
            'class' => ReCaptchaConfig::class,
        ],
        'user' => [
            'class' => yii\web\User::class,
            'identityClass' => User::class,
        ],
    ],
];
