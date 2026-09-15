<?php

use app\models\Receipt;

/**
 * @var Receipt $receipt
 */

?>

<?php echo $receipt->id; ?>
<br>
<small class="text-muted">
    <?= Yii::t('app', 'Created by') ?>
    <?php echo $receipt->createdBy->name; ?>
    <?php echo Yii::$app->formatter->asDatetime($receipt->created_at); ?>
</small>