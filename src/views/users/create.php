<?php

use app\components\widgets\ActiveForm;
use app\models\user\Create;
use app\widgets\FormSubmit\FormSubmit;

/**
 * @var Create $model
 */

$this->title = Yii::t('app', 'New user');

?>

<div class="card">
    <div class="card-header">
        <?php echo $this->title; ?>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="card-body">
        <?php echo $this->render('save/form', [
            'form' => $form,
            'model' => $model,
        ]); ?>
        <div class="row">
            <div class="col-12 col-md-4">
                <?php echo $form
                    ->field($model, 'password', [
                        'inputOptions' => [
                            'minlength' => Create::PASSWORD_MIN_LENGTH,
                        ],
                    ])->passwordInput(); ?>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <?php echo FormSubmit::widget(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
