<?php

use app\components\widgets\ActiveForm;
use app\widgets\FormSubmit\FormSubmit;
use app\widgets\ProductsStorageCells\ProductsStorageCells;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var \app\components\widgets\ActiveForm $form
 * @var \app\models\shipment_product\Save $model
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
            <?php echo $form->errorSummary([$model]); ?>

            <div class="row">
                <div class="col-12 col-md-4">
                    <?php echo $form
                        ->field($model, 'productId')
                        ->widget(Select2::class, [
                            'initValueText' => $model->getProductIdValueText(),
                            'pluginOptions' => [
                                'ajax' => [
                                    'url' => Url::to(['/shipments-products/autocomplete-products']),
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {query:params.term}; }')
                                ],
                                'minimumInputLength' => 2,
                            ]]); ?>
                </div>
            </div>
            <?php if (Yii::$app->getRequest()->getIsPost() && !empty($model->productId)) { ?>
                <?php echo ProductsStorageCells::widget([
                    'productId' => $model->productId,
                ]); ?>
            <?php } else { ?>
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




