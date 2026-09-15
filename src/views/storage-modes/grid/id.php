<?php

use app\models\StorageMode;

/**
 * @var StorageMode $storageMode
 */

?>

<?php echo $storageMode->id; ?>
<br>
<small class="text-muted">
    <?= Yii::t('app', 'Created by') ?>
    <?php echo $storageMode->createdBy->name; ?>
    <?php echo Yii::$app->formatter->asDatetime($storageMode->created_at); ?>
</small>