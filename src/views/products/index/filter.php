<?php

use app\components\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var \app\models\product\Index $model
 */

?>

<div class="card" style="clear:both;margin-top:48px;">
    <div class="card-header <?php if ($model->filterEnabled()) { ?>bg-primary text-white<?php } ?>">
        <div class="panel-title">
            <?= Yii::t('app', 'Filter') ?> <?php if ($model->filterEnabled()) { ?><?= Yii::t('app', 'applied') ?><?php } ?>
        </div>
    </div>
    <?php $form = ActiveForm::begin(['method'=>'get']); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                <?php echo $form->field($model, 'name') ?>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                <?php echo $form
                    ->field($model, 'producer')
                    ->widget(Select2::class, [
                        'initValueText' => $model->producer,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'ajax' => [
                                'url' => Url::to(['/producers/autocomplete']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {query:params.term}; }')
                            ],
                            'minimumInputLength' => 0,
                            'placeholder' => '',
                        ]]); ?>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                <?php echo $form
                    ->field($model, 'storageModeId')
                    ->dropDownList([0 => ''] + $model->getStorageModeIdOptions()); ?>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button class="btn btn-primary" type="submit">
                <?= Yii::t('app', 'Apply filter') ?>
            </button>
            <?php if ($model->filterEnabled()) { ?>
                <a class="btn btn-danger" href="/products/index"><?= Yii::t('app', 'Reset filter') ?></a>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


