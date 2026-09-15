<?php

use app\models\StorageCell;

/**
 * @var StorageCell $storageCell
 */

?>

<?php echo $storageCell->id; ?>
<br>
<small class="text-muted">
    <?= Yii::t('app', 'Created by') ?>
    <?php echo $storageCell->createdBy->name; ?>
    <?php echo Yii::$app->formatter->asDatetime($storageCell->created_at); ?>
</small>