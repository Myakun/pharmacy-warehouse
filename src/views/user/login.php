<?php

use app\components\widgets\ActiveForm;
use app\models\user\Login;
use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\bootstrap5\BootstrapAsset;
use yii\helpers\Html;

/**
 * @var Login $model
 */

$this->beginPage();
?>
<!doctype html>
<html lang="ru">
<head>
    <?php $scheme = Yii::$app->getRequest()->getIsSecureConnection() ? 'https' : 'http'; ?>
    <base href="<?php echo $scheme . '://' . Yii::$app->getRequest()->getServerName() . Yii::$app->getRequest()->getBaseUrl(); ?>">
    <meta charset="utf-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title><?= Yii::t('app', 'Sign in') ?></title>
    <?php BootstrapAsset::register($this); ?>
    <?php $this->head(); ?>
</head>
    <body>
        <?php $this->beginBody() ?>
        <div class="container">
            <div class="d-flex align-justify-content-md-center justify-content-center mt-5">
                <?php
                $form = ActiveForm::begin();
                echo $form->errorSummary($model);
                echo $form
                    ->field($model, 'email')
                    ->input('email');
                echo $form
                    ->field($model, 'password')
                    ->input('password');
                echo $form
                    ->field($model, 'reCaptcha')
                    ->widget(ReCaptcha3::class)
                    ->error(false)
                    ->label(false)
                ?>
                <div class="d-grid">
                    <?php echo Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

