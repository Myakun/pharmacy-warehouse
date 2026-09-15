<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\Product;
use app\models\ProductStorageCell;
use app\models\Receipt;
use app\models\receipt_product\Save;
use app\models\ReceiptProduct;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ReceiptsProductsController extends Controller
{
    use CRUDTrait;

    public function actionDelete(int $id): Response
    {
        $receiptProduct = ReceiptProduct::findOne($id);
        if (null == $receiptProduct) {
            throw new NotFoundHttpException();
        }

        foreach ($receiptProduct->productsStorageCells as $productsStorageCell) {
            if ($productsStorageCell->out > 0) {
                throw new ForbiddenHttpException();
            }
        }

        return $this->delete($receiptProduct, [
            'returnUrl' => Url::to(['index', 'receiptId' => $receiptProduct->receipt_id]),
            'successMessage' => Yii::t('app', 'Product removed from the receipt'),
        ]);
    }

    public function actionIndex(int $receiptId): Response
    {
        $receipt = Receipt::findOne($receiptId);
        if (null == $receipt) {
            throw new NotFoundHttpException();
        }

        $entity = new ReceiptProduct();
        $entity->receipt_id = $receiptId;

        $model = new Save($entity);

        $storageCells = [];
        if (Yii::$app->getRequest()->getIsPost() && isset($_POST['storage-cells'])) {
            foreach ($_POST['storage-cells'] as $storageCellId => $amount) {
                if (empty($amount)) {
                    continue;
                }

                $storageCells[$storageCellId] = $amount;
            }
        }
        $model->storageCells = $storageCells;

        $response = $this->save($model, [
            'afterSave' => [
                'redirect' => [
                    'create' => Url::to(['index', 'receiptId' => $receiptId, 'showForm' => true]),
                    'save' => Url::to(['index', 'receiptId' => $receiptId]),
                ],
            ],
        ]);

        if (null != $response) {
            foreach ($storageCells as $storageCellId => $amount) {
                $receiptProductStorageCell = new ProductStorageCell();
                $receiptProductStorageCell->amount = $amount;
                $receiptProductStorageCell->in = $amount;
                $receiptProductStorageCell->receipt_product_id = $model->getEntity()->id;
                $receiptProductStorageCell->storage_cell_id = $storageCellId;
                $receiptProductStorageCell->save();
            }

            Yii::$app->getSession()->setFlash('successMessage', Yii::t('app', 'Product added to the receipt'));

            return $response;
        }

        $receiptDataProvider = new ArrayDataProvider([
            'allModels' => [$receipt],
            'key' => 'id',
        ]);

        return $this->index([
            'dataProvider' => [
                'pagination' => false,
                'query' => ReceiptProduct::find()
                    ->innerJoinWith(['createdBy', 'product', 'productsStorageCells'])
                    ->andWhere(['receipt_id' => $receiptId])
                    ->orderBy('products.name'),
            ],
            'viewParams' => [
                'model' => $model,
                'receipt' => $receipt,
                'receiptDataProvider' => $receiptDataProvider,
                'selectedStorageCells' => $storageCells,
            ],
        ]);
    }

    public function actionSeriesAutocomplete(string $query = ''): array
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $result = ['results' => []];

        if ('' == $query) {
            return $result;
        }

        $query = ReceiptProduct::find()
            ->select(['series'])
            ->andWhere(['like', 'lower(series)', mb_strtolower(trim($query))])
            ->limit(10)
            ->asArray();

        foreach ($query->all() as $row) {
            $result['results'][] = [
                'id' => $row['series'],
                'text' => $row['series'],
            ];
        }

        return $result;
    }

    public function actionStorageCells(int $packagesAmount, int $productId, string $series): string
    {
        $product = Product::findOne($productId);
        if (null == $product) {
            throw new NotFoundHttpException();
        }

        return $this->renderPartial('storage-cells', [
            'packagesAmount' => $packagesAmount,
            'productId' => $productId,
            'series' => $series,
        ]);
    }
}
