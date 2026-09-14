<?php

declare(strict_types=1);

namespace app\widgets\FormSubmit;

use yii\base\Widget;
use yii\helpers\Url;

class FormSubmit extends Widget
{
    public bool $buttonCancel = true;

    public ?string $buttonCancelUrl = null;

    public bool $buttonSave = true;

    public bool $buttonSaveAndAdd = true;

    public bool $buttonSaveAndEdit = true;

    public function run(): string
    {
        if ($this->buttonCancelUrl === null) {
            $this->buttonCancelUrl = Url::toRoute('index');
        }

        return $this->render('form-submit-widget', [
            'buttonCancel' => $this->buttonCancel,
            'buttonCancelUrl' => $this->buttonCancelUrl,
            'buttonSave' => $this->buttonSave,
            'buttonSaveAndAdd' => $this->buttonSaveAndAdd,
            'buttonSaveAndEdit' => $this->buttonSaveAndEdit,
        ]);
    }
}
