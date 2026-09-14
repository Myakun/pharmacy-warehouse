<?php

declare(strict_types=1);

namespace app\assets\shipments_products;

use yii\web\AssetBundle;

class Index extends AssetBundle
{
    public $sourcePath = '@app/assets/shipments_products/src';

    public $js = [
        'js/shipments-products-index.js',
    ];

    public function init()
    {
        parent::init();

        $this->js[0] = $this->js[0] . '?v=' . time();
    }

    public $depends = [
        \yii\web\YiiAsset::class,
    ];
}
