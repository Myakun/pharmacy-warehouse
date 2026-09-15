<?php

use app\models\User;

/**
 * @var User $user
 */

?>

<?php echo $user->id; ?>
<br>
<small class="text-muted">
    <?= Yii::t('app', 'Created') ?>
    <?php echo Yii::$app->formatter->asDatetime($user->created_at); ?>
    <?php if (null != $user->created_by) { ?>
        <?= Yii::t('app', 'by') ?>
        <?php echo $user->createdBy->name; ?>
    <?php } ?>
</small>