<?php

use app\assets\receipts_products\Index;
use app\components\widgets\grid\GridView;
use app\models\Shipment;
use kartik\helpers\Html;

/**
 * @var \app\models\ShipmentProduct $shipmentProduct
 */

?>

<?php echo $shipmentProduct->id; ?>
<br>
<small class="text-muted">
    <?= Yii::t('app', 'Created by') ?>
    <?php echo $shipmentProduct->createdBy->name; ?>
    <?php echo Yii::$app->formatter->asDatetime($shipmentProduct->created_at); ?>
</small>