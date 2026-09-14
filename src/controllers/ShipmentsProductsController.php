<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\Product;
use app\models\Shipment;
use app\models\shipment_product\Save;
use app\models\ShipmentProduct;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ShipmentsProductsController extends Controller
{
    use CRUDTrait;

    public function actionAutocompleteProducts(string $query = ''): array
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $result = ['results' => []];

        if ('' == $query) {
            return $result;
        }

        $query = Product::find()
            ->select(['products.id', 'name'])
            ->andWhere(['like', 'lower(name)', mb_strtolower(trim($query))])
            ->innerJoinWith(['receiptsProducts' => function (ActiveQuery $query) {
                $query->innerJoinWith(['productsStorageCells' => function (ActiveQuery $query) {
                    $query->andWhere('amount > 0');
                }]);
            }])
            ->limit(10)
            ->asArray();

        foreach ($query->all() as $row) {
            $result['results'][] = [
                'id' => $row['id'],
                'text' => $row['name'],
            ];
        }

        return $result;
    }

    public function actionDelete(int $id): Response
    {
        $shipmentProduct = ShipmentProduct::findOne($id);
        if (null == $shipmentProduct) {
            throw new NotFoundHttpException();
        }

        return $this->delete($shipmentProduct, [
            'returnUrl' => Url::to(['index', 'shipmentId' => $shipmentProduct->shipment_id]),
            'successMessage' => 'Товар успешно удален из отгрузки',
        ]);
    }

    public function actionIndex(int $shipmentId): Response
    {
        $shipment = Shipment::findOne($shipmentId);
        if (null == $shipment) {
            throw new NotFoundHttpException();
        }

        $model = new Save();

        if ($model->load(Yii::$app->getRequest()->post())) {
            $amounts = [];
            if (isset($_POST['amounts'])) {
                foreach ($_POST['amounts'] as $productStorageCellId => $amount) {
                    if (0 == $amount) {
                        continue;
                    }

                    $amounts[$productStorageCellId] = $amount;
                }
            }
            $model->amounts = $amounts;

            if ($model->validate()) {
                foreach ($amounts as $productStorageCellId => $amount) {
                    if (0 == $amount) {
                        continue;
                    }

                    $shipmentProduct = new ShipmentProduct();
                    $shipmentProduct->packages_amount = $amount;
                    $shipmentProduct->shipment_id = $shipmentId;
                    $shipmentProduct->product_storage_cell_id = $productStorageCellId;
                    $shipmentProduct->save();
                }

                if (Yii::$app->getRequest()->post('save')) {
                    return $this->redirect(Url::to(['index', 'shipmentId' => $shipmentId]));
                }

                if (Yii::$app->getRequest()->post('save-and-add')) {
                    return $this->redirect(Url::to(['index', 'shipmentId' => $shipmentId, 'showForm' => true]));
                }
            }
        }

        $shipmentDataProvider = new ArrayDataProvider([
            'allModels' => [$shipment],
            'key' => 'id',
        ]);

        return $this->index([
            'dataProvider' => [
                'pagination' => false,
                'query' => ShipmentProduct::find()
                    ->andWhere(['shipment_id' => $shipmentId])
                    ->with([
                        'createdBy',
                        'productStorageCell' => function ($query) {
                            $query->with(['receiptProduct' => function ($query) {
                                $query->with('product');
                            },
                        ]);
                        }]),
            ],
            'viewParams' => [
                'model' => $model,
                'shipment' => $shipment,
                'shipmentDataProvider' => $shipmentDataProvider,
            ],
        ]);
    }

    public function actionProductsStorageCells(int $productId): string
    {
        $product = Product::findOne($productId);
        if (null == $product) {
            throw new NotFoundHttpException();
        }

        return $this->renderPartial('products-storage-cells', [
            'productId' => $productId,
        ]);
    }
}
