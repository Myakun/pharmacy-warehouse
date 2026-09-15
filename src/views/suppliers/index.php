<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;
use app\models\Supplier;
use yii\helpers\Html;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\models\supplier\Index $filterModel
 */

$this->title = Yii::t('app', 'Suppliers');

?>

<?php echo GridView::widget([
    'columns' => include(__DIR__ . '/grid/columns.php'),
    'dataProvider' => $dataProvider,
    'filterModel' => $filterModel,
    'panel' => [
        'after' => false,
        'heading' => $this->title,
    ],
    'toolbar' => Yii::$app->getUser()->can(Supplier::PERMISSION_MANAGE) ? [
        'content' => Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success'])
    ] : false,
]); ?>