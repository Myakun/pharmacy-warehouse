<?php

declare(strict_types=1);

namespace app\assets\receipts_products;

use yii\web\AssetBundle;

class Index extends AssetBundle
{
    public $sourcePath = '@app/assets/receipts_products/src';

    public $css = [
        'css/receipts-products-index.css',
    ];

    public $js = [
        'js/receipts-products-index.js',
    ];

    public function init()
    {
        parent::init();

        $this->css[0] = $this->css[0] . '?v=' . time();
        $this->js[0] = $this->js[0] . '?v=' . time();
    }

    public $depends = [
        \yii\web\YiiAsset::class,
    ];
}
