<?php

declare(strict_types=1);

require __DIR__ . '/../components/bootstrap.php';

$config = require(__DIR__ . '/../config/web.php');

try {
    new \yii\web\Application($config)->run();
} catch (\yii\base\InvalidConfigException $e) {
    echo $e->getMessage();
}
