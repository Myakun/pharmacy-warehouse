<?php

use app\assets\receipts_products\Index;
use app\components\widgets\grid\GridView;
use app\models\Receipt;
use kartik\helpers\Html;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\receipt_product\Save $model
 * @var \app\models\Receipt $receipt
 * @var \yii\data\ArrayDataProvider $receiptDataProvider
 * @var array $selectedStorageCells
 */

$this->title = sprintf(
    Yii::t('app', 'Products of receipt %s dated %s'),
    Receipt::INVOICE_NUMBER_PREFIX . $receipt->invoice_number,
    $receipt->invoice_date
);

echo GridView::widget([
    'columns' => include(__DIR__ . '/../receipts/grid/columns.php'),
    'dataProvider' => $receiptDataProvider,
    'summary' => false,
]);

echo GridView::widget([
    'columns' => include(__DIR__ . '/grid/columns.php'),
    'dataProvider' => $dataProvider,
    'panel' => [
        'after' => false,
        'before' => $this->render('save/form', [
            'model' => $model,
            'selectedStorageCells' => $selectedStorageCells,
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
