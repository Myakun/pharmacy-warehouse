<?php

declare(strict_types=1);

use app\models\User;
use himiklab\yii2\recaptcha\ReCaptchaConfig;
use yii\helpers\ArrayHelper;

$params = include __DIR__ . '/params.php';

$config = [
    'components' => [
        'assetManager' => [
            'basePath' => APP_ENV == 'dev' ? '@runtime/assets' : '@webroot/assets',
            'linkAssets' => APP_ENV == 'dev',
        ],
        'reCaptcha' => [
            'class' => ReCaptchaConfig::class,
            'secretV3' => $params['reCaptcha']['secretKey'],
            'siteKeyV3' => $params['reCaptcha']['siteKey'],
        ],
        'request' => [
            'cookieValidationKey' => $params['app']['cookieValidationKey'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'user' => [
            'enableAutoLogin' => true,
            'identityClass' => User::class,
            'loginUrl' => ['/user/login'],
        ],
    ],
    'defaultRoute' => 'default/index',
    'modules' => [
        'gridview' => [
            'class' => kartik\grid\Module::class,
        ],
    ],
    'params' => [
        'bsVersion' => '5.x',
    ],
];

if (APP_ENV === 'dev') {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => yii\debug\Module::class,
        'allowedIPs' => ['127.0.0.1', '::1', '*.*.*.*'],
    ];
}

return ArrayHelper::merge($config, include(__DIR__ . '/main.php'));
