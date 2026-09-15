<?php

use app\assets\shipments_products\Index;
use app\components\widgets\grid\GridView;
use app\models\Shipment;
use kartik\helpers\Html;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\receipt_product\Save $model
 * @var \app\models\Shipment $shipment
 * @var \yii\data\ArrayDataProvider $shipmentDataProvider
 */

$this->title = sprintf(
    Yii::t('app', 'Products of shipment %s dated %s'),
    Shipment::INVOICE_NUMBER_PREFIX . $shipment->invoice_number,
    $shipment->invoice_date
);

echo GridView::widget([
    'columns' => include(__DIR__ . '/../shipments/grid/columns.php'),
    'dataProvider' => $shipmentDataProvider,
    'summary' => false,
]);

echo GridView::widget([
    'columns' => include(__DIR__ . '/grid/columns.php'),
    'dataProvider' => $dataProvider,
    'panel' => [
        'after' => false,
        'before' => $this->render('save/form', [
            'model' => $model
        ]),
        'heading' => $this->title,
    ],
    'toolbar' => [
        'content' => Html::a(Yii::t('app', 'Create'), ['create'], [
            'class' => 'btn btn-success',
            'id' => 'add-product',
        ])
    ]
]);

?>

<style>
    #save-product-form {
        display:none;
    }

    <?php if (Yii::$app->getRequest()->getIsPost() || Yii::$app->getRequest()->get('showForm')) { ?>
        #add-product {
            display:none;
        }

        #save-product-form {
            display:block;
        }
    <?php } ?>
</style>

<?php

Index::register($this);
