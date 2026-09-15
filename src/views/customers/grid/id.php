<?php

use app\models\Customer;

/**
 * @var Customer $customer
 */

?>

<?php echo $customer->id; ?>
<br>
<small class="text-muted">
    <?= Yii::t('app', 'Created by') ?>
    <?php echo $customer->createdBy->name; ?>
    <?php echo Yii::$app->formatter->asDatetime($customer->created_at); ?>
</small>