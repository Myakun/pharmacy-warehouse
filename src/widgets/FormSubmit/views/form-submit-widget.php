<?php

/**
 * @var bool $buttonCancel
 * @var string $buttonCancelUrl
 * @var bool $buttonSave
 * @var bool $buttonSaveAndAdd
 * @var bool $buttonSaveAndEdit
 */

?>

<div class="d-flex justify-content-between form-submit">
    <?php if ($buttonSave) { ?>
        <div>
            <button class="btn btn-success save" name="save"  type="submit" value="save">
                <?= Yii::t('app', 'Save') ?>
            </button>
        </div>
    <?php } ?>
    <?php if ($buttonSaveAndEdit || $buttonSaveAndAdd) { ?>
    <div>
        <?php if ($buttonSaveAndEdit) { ?>
            <button class="btn btn-primary save-and-edit" name="save-and-edit" type="submit" value="save-and-edit">
                <span class="fas fa-pencil"></span> <?= Yii::t('app', 'Save and edit') ?>
            </button>
        <?php } ?>
        <?php if ($buttonSaveAndAdd) { ?>
            <?php if ($buttonSaveAndEdit) { ?>
                &nbsp;&nbsp;
            <?php } ?>
            <button class="btn btn-primary save-and-add" name="save-and-add" type="submit" value="save-and-add">
                <span class="fas fa-plus"></span> <?= Yii::t('app', 'Save and add another') ?>
            </button>
        <?php } ?>
    </div>
    <?php } ?>
    <?php if ($buttonCancel) { ?>
        <div>
            <a href="<?php echo $buttonCancelUrl; ?>" class="btn btn-danger cancel"><?= Yii::t('app', 'Cancel') ?></a>
        </div>
    <?php } ?>
</div>
