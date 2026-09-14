<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\Receipt;
use app\models\receipt\Index;
use app\models\receipt\Save;
use app\models\ReceiptProduct;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ReceiptsController extends Controller
{
    use CRUDTrait;

    public function actionCreate(): Response
    {
        return $this->create(new Save(new Receipt()), [
            'successMessage' => 'Приход успешно создан',
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $receipt = Receipt::findOne($id);
        if (null == $receipt) {
            throw new NotFoundHttpException();
        }

        if (!empty($receipt->receiptProducts)) {
            throw new ForbiddenHttpException();
        }

        return $this->delete($receipt, [
            'successMessage' => 'Приход успешно удален',
        ]);
    }

    public function actionIndex(): Response
    {
        $filterModel = new Index();
        $attributes = Yii::$app->getRequest()->get();
        $filterModel->load($attributes);

        return $this->index([
            'dataProvider' => [
                'query' => $filterModel->getQuery(),
            ],
            'viewParams' => [
                'filterModel' => $filterModel,
            ],
        ]);
    }

    public function actionProducts(): string
    {
        if (!isset($_POST['expandRowKey'])) {
            throw new NotFoundHttpException();
        }

        return $this->renderPartial('products', [
            'dataProvider' => new ActiveDataProvider([
                'pagination' => false,
                'query' => ReceiptProduct::find()
                    ->innerJoinWith(['createdBy', 'product', 'productsStorageCells'])
                    ->andWhere(['receipt_id' => $_POST['expandRowKey']])
                    ->orderBy('products.name'),
            ]),
            'disableDelete' => false,
        ]);
    }

    public function actionUpdate(int $id): Response
    {
        $customer = Receipt::findOne($id);
        if (null == $customer) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(Receipt::findOne($id)), [
            'successMessage' => 'Приход успешно изменён',
        ]);
    }
}
