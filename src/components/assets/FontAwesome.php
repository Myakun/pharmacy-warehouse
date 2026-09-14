<?php

declare(strict_types=1);

namespace app\components\assets;

use yii\web\AssetBundle;

class FontAwesome extends AssetBundle
{
    public $sourcePath = '@vendor/components/font-awesome';

    public $css = [
        'css/all.css',
    ];
}
