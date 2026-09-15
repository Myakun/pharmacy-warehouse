<?php

use app\components\widgets\ActiveForm;
use app\widgets\FormSubmit\FormSubmit;
use app\widgets\StorageCells\StorageCells;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var \app\components\widgets\ActiveForm $form
 * @var \app\models\receipt_product\Save $model
 * @var array $selectedStorageCells
 */

?>

<?php $form = ActiveForm::begin([
    'id' => 'save-product-form',
]); ?>
    <div class="card">
        <div class="card-header">
            <?= Yii::t('app', 'New product') ?>
        </div>
        <div class="card-body">
            <?php echo $form->errorSummary([$model, $model->getEntity()]); ?>

            <div class="row">
                <div class="col-12 col-md-4">
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
                                'minimumInputLength' => 2,
                            ]]); ?>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 col-md-2">
                    <?php echo $form
                        ->field($model, 'series')
                        ->widget(Select2::class, [
                            'initValueText' => $model->series,
                            'pluginOptions' => [
                                'ajax' => [
                                    'url' => Url::to(['/receipts-products/series-autocomplete']),
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {query:params.term}; }')
                                ],
                                'minimumInputLength' => 2,
                                'tags' => true,
                            ]]); ?>
                </div>
                <div class="col-6 col-md-2">
                    <?php echo $form->field($model, 'packagesAmount'); ?>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-6 col-md-2">
                    <?php echo $form
                        ->field($model, 'productionDate')
                        ->widget(DatePicker::class, [
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy'
                            ],
                            'type' => DatePicker::TYPE_INPUT,
                        ]); ?>
                </div>
                <div class="col-6 col-md-2">
                    <?php echo $form
                        ->field($model, 'expirationDate')
                        ->widget(DatePicker::class, [
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy'
                            ],
                            'type' => DatePicker::TYPE_INPUT,
                        ]); ?>
                </div>
            </div>
            <?php if (Yii::$app->getRequest()->getIsPost() && !empty($selectedStorageCells)) { ?>
                <div id="save-storage-cells-container">
                    <?php echo StorageCells::widget([
                        'productId' => $model->productId,
                        'packagesAmount' => $model->packagesAmount,
                        'selectedStorageCells' => $selectedStorageCells,
                        'series' => $model->series,
                    ]); ?>
                </div>
            <?php } else {  ?>
                <div class="d-none" id="save-storage-cells-container"></div>
            <?php } ?>
        </div>
        <div class="card-footer">
            <?php echo FormSubmit::widget([
                'buttonSaveAndEdit' => false,
            ]); ?>
        </div>
</div>

<?php ActiveForm::end(); ?>




