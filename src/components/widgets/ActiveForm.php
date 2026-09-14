<?php

declare(strict_types=1);

namespace app\components\widgets;

class ActiveForm extends \kartik\widgets\ActiveForm
{
    public $enableClientScript = false;

    public $enableClientValidation = false;

    public $errorSummaryCssClass = 'alert alert-danger';

    public function errorSummary($models, $options = []): string
    {
        if (!isset($options['header'])) {
            $options['header'] = '';
        }

        return parent::errorSummary($models, $options);
    }
}
