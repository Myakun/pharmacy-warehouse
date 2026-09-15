<?php

use app\components\widgets\ActiveForm;
use app\models\storage_mode\Save;
use app\widgets\FormSubmit\FormSubmit;

/**
 * @var Save $model
 */

$this->title = Yii::t('app', 'New storage condition');

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
    </div>
    <div class="card-footer">
        <?php echo FormSubmit::widget(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
