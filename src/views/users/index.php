<?php

declare(strict_types=1);

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use app\components\widgets\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Users');

?>

<?php echo GridView::widget([
    'columns' => include(__DIR__ . '/grid/columns.php'),
    'dataProvider' => $dataProvider,
    'panel' => [
        'after' => false,
        'heading' => $this->title,
    ],
    'summary' => false,
    'toolbar' => [
        'content' => Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success'])
    ]
]); ?>