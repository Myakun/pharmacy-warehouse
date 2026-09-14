<?php

declare(strict_types=1);

use yii\console\controllers\MigrateController;
use yii\helpers\ArrayHelper;

return ArrayHelper::merge([
    'controllerMap' => [
        'migrate' => [
              'class' => MigrateController::class,
              'migrationPath' => [
                  '@app/migrations',
                  '@yii/rbac/migrations',
              ],
          ],
    ],
    'controllerNamespace' => 'app\commands',
], include(__DIR__ . '/main.php'));
