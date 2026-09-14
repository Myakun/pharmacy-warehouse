<?php

declare(strict_types=1);

namespace app\components\widgets\grid;

class GridView extends \kartik\grid\GridView
{
    public $persistResize = true;

    public $striped = false;

    public $hover = true;

    public $resizableColumns = false;
}
