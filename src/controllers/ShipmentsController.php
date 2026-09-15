<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\Shipment;
use app\models\shipment\Index;
use app\models\shipment\Save;
use app\models\ShipmentProduct;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ShipmentsController extends Controller
{
    use CRUDTrait;

    public function actionCreate(): Response
    {
        return $this->create(new Save(new Shipment()), [
            'successMessage' => Yii::t('app', 'Shipment created'),
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $shipment = Shipment::findOne($id);
        if (null == $shipment) {
            throw new NotFoundHttpException();
        }

        if (!empty($shipment->shipmentProducts)) {
            throw new ForbiddenHttpException();
        }

        return $this->delete($shipment, [
            'successMessage' => Yii::t('app', 'Shipment deleted'),
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
                'query' => ShipmentProduct::find()
                    ->andWhere(['shipment_id' => $_POST['expandRowKey']])
                    ->with([
                        'createdBy',
                        'productStorageCell' => function ($query) {
                            $query->with(['receiptProduct' => function ($query) {
                                $query->with('product');
                            }]);
                        },
                    ]),
            ]),
            'disableDelete' => false,
        ]);
    }

    public function actionUpdate(int $id): Response
    {
        $customer = Shipment::findOne($id);
        if (null == $customer) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(Shipment::findOne($id)), [
            'successMessage' => Yii::t('app', 'Shipment updated'),
        ]);
    }
}
