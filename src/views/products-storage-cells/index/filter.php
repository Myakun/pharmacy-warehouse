<?php

use app\components\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var \app\models\product_storage_cell\Index $model
 */

?>

<div class="card">
    <div class="card-header <?php if ($model->filterEnabled()) { ?>bg-primary text-white<?php } ?>">
        <div class="panel-title">
            <?= Yii::t('app', 'Filter') ?> <?php if ($model->filterEnabled()) { ?><?= Yii::t('app', 'applied') ?><?php } ?>
        </div>
    </div>
    <?php $form = ActiveForm::begin(['method'=>'get']); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-xs-12">
                <?php echo $form
                    ->field($model, 'productId')
                    ->widget(Select2::class, [
                        'initValueText' => $model->getProductIdValueText(),
                        'pluginOptions' => [
                            'ajax' => [
                                'url' => Url::to(['/products/autocomplete']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {query:params.term, useId: true}; }')
                            ],
                            'allowClear' => true,
                            'minimumInputLength' => 2,
                            'placeholder' => '',
                        ]]); ?>
            </div>
            <div class="col-lg-2 col-md-3 col-xs-12">
                <?php echo $form
                    ->field($model, 'series')
                    ->widget(Select2::class, [
                        'initValueText' => $model->series,
                        'pluginOptions' => [
                            'ajax' => [
                                'url' => Url::to(['/products-storage-cells/autocomplete-series']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {query:params.term}; }')
                            ],
                            'allowClear' => true,
                            'minimumInputLength' => 2,
                            'placeholder' => '',
                        ]]); ?>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button class="btn btn-primary" type="submit">
                <?= Yii::t('app', 'Apply filter') ?>
            </button>
            <?php if ($model->filterEnabled()) { ?>
                <a class="btn btn-danger" href="/products-storage-cells/index"><?= Yii::t('app', 'Reset filter') ?></a>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


