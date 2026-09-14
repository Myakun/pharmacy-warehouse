<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
