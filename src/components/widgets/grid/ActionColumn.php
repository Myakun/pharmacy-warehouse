<?php

declare(strict_types=1);

namespace app\components\widgets\grid;

class ActionColumn extends \kartik\grid\ActionColumn
{
    public $contentOptions = [
        'class' => 'action-column',
    ];

    public $deleteOptions = [
        'class' => 'btn btn-light btn-sm',
    ];

    public $template = '{update} {delete}';

    public $updateOptions = [
        'class' => 'btn btn-light btn-sm',
    ];

    public $vAlign = GridView::ALIGN_TOP;

    public $viewOptions = [
        'class' => 'btn btn-light btn-sm',
    ];
}
