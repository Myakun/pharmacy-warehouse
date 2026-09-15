<?php

use app\models\Supplier;

/**
 * @var Supplier $supplier
 */

?>

<?php echo $supplier->id; ?>
<br>
<small class="text-muted">
    <?= Yii::t('app', 'Created by') ?>
    <?php echo $supplier->createdBy->name; ?>
    <?php echo Yii::$app->formatter->asDatetime($supplier->created_at); ?>
</small>