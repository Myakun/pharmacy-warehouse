<?php

use app\components\widgets\ActiveForm;
use app\models\Shipment;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var \app\models\shipment\Index $model
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
            <div class="col-lg-2 col-md-4 col-xs-12">
                <?php echo $form->field($model, 'invoiceNumber', [
                    'addon' => [
                        'prepend' => [
                            'content' => Shipment::INVOICE_NUMBER_PREFIX
                        ]
                    ]
                ]) ?>
            </div>
            <div class="col-lg-2 col-md-4 col-xs-12">
                <?php echo $form
                    ->field($model, 'invoiceDateFrom')
                    ->widget(DatePicker::class, [
                        'attribute2' => 'invoiceDateTo',
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy'
                        ],
                        'separator' => '-',
                        'type' => DatePicker::TYPE_RANGE,
                    ]); ?>
            </div>
            <div class="col-lg-3 col-md-4 col-xs-12">
                <?php echo $form
                    ->field($model, 'customerId')
                    ->widget(Select2::class, [
                        'initValueText' => $model->getCustomerIdValueText(),
                        'pluginOptions' => [
                            'ajax' => [
                                'url' => Url::to(['/customers/autocomplete']),
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
                <a class="btn btn-danger" href="/shipments/index"><?= Yii::t('app', 'Reset filter') ?></a>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


