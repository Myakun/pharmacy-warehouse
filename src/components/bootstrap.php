<?php

declare(strict_types=1);

defined('APP_ENV') || define('APP_ENV', getenv('APP_ENV'));
defined('YII_DEBUG') || define('YII_DEBUG', APP_ENV == 'dev');
defined('YII_ENV') || define('YII_ENV', APP_ENV);

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

if (YII_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
}

Yii::setAlias('app', __DIR__ . '/../');
Yii::setAlias('@bower', __DIR__ . '/../../vendor/bower-asset');