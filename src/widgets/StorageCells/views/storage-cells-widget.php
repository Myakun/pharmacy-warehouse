<?php

declare(strict_types=1);

use app\assets\receipts_products\Index;
use app\models\Product;

/**
 * @var int $packagesAmount
 * @var Product $product
 * @var array $rows
 */

?>

<div id="storage-cells-widget">
    <h5><?= Yii::t('app', 'Storage cells') ?></h5>

    <div class="alert alert-warning" id="storage-cells-widget-packages-amount">
        <?= Yii::t('app', 'Selected cells hold') ?>
        <span class="selected" data-amount="0">0</span>
        <?= Yii::t('app', 'of') ?>
        <span class="required" data-amount="<?php echo $packagesAmount; ?>">
            <?php echo $packagesAmount; ?>
        </span>
        <?= Yii::t('app', 'packages.') ?>
    </div>

    <div class="row" id="storage-cells-widget-storage-cells">
        <?php foreach ($rows as $rowNumber => $row) { ?>
            <div class="col-12 col-md-2 storage-cells-widget-row">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center storage-cells-widget-row-number">
                            <?php echo $row['name']; ?>
                        </th>
                    </tr>
                    <?php foreach ($row['racks'] as $rackNumber => $rack) { ?>
                        <?php if ($rackNumber % 2 == 1) { ?>
                            <tr>
                        <?php } ?>
                        <td>
                            <table class="table storage-cells-widget-racks">
                                <tr>
                                    <th class="text-center storage-cells-widget-rack-number">
                                        <?php echo $rackNumber; ?>
                                    </th>
                                </tr>
                                <?php foreach ($rack['shelfs'] as $shelfNumber => $shelf) { ?>
                                    <tr>
                                        <td class="storage-cell
                                        <?php if ($shelf['anotherPackages'] || $shelf['anotherSeries'] || 0 == $shelf['maxPackages']) { ?>
                                            table-danger
                                        <?php } ?>
                                        <?php if ($shelf['selected']) { ?>
                                            table-success
                                        <?php } ?>
                                        "
                                            data-max-packages="<?php echo $shelf['maxPackages']; ?>"
                                        >
                                            <b><?php echo $shelfNumber; ?></b>
                                            <span class="storage-cell-volume">
                                                <?php if ($shelf['anotherPackages']) { ?>
                                                    <?= Yii::t('app', 'other package size') ?>
                                                <?php } elseif ($shelf['anotherSeries']) { ?>
                                                    <?= Yii::t('app', 'other batch') ?>
                                                <?php } elseif (0 == $shelf['maxPackages']) { ?>
                                                    <?= Yii::t('app', 'no space') ?>
                                                <?php } else { ?>
                                                    <?= Yii::t('app', 'up to {n} pcs', ['n' => $shelf['maxPackages']]) ?>
                                                <?php } ?>
                                            </span>
                                            <br>
                                            <button class="btn btn-primary btn-sm select-cell"><?= Yii::t('app', 'Select') ?></button>
                                            <button class="btn btn-danger btn-sm unselect-cell"><?= Yii::t('app', 'Remove') ?></button>
                                            <input
                                                    name="storage-cells[<?php echo $shelf['id']; ?>]"
                                                    <?php if ($shelf['selected']) { ?>
                                                        value="<?php echo $shelf['selectedAmount']; ?>"
                                                    <?php } ?>
                                                    type="hidden">
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                        <?php if ($rackNumber % 2 == 0) { ?>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </div>
        <?php } ?>
    </div>
</div>

<?php Index::register($this); ?>