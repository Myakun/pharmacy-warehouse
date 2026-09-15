<?php

use app\models\Product;

/**
 * @var Product $product
 */

?>

<?php echo $product->id; ?>
<br>
<small class="text-muted">
    <?= Yii::t('app', 'Created by') ?>
    <?php echo $product->createdBy->name; ?>
    <?php echo Yii::$app->formatter->asDatetime($product->created_at); ?>
</small>