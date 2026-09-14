<?php

declare(strict_types=1);

namespace app\widgets\ProductsStorageCells;

use app\models\Product;
use app\models\product_storage_cell\Index;
use app\models\ProductStorageCell;
use DateTimeImmutable;
use Yii;
use yii\base\Widget;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

class ProductsStorageCells extends Widget
{
    public int $packagesAmount;

    public int $productId;

    public function run(): string
    {
        $product = Product::findOne($this->productId);
        if (null == $product) {
            throw new NotFoundHttpException();
        }

        $filterModel = new Index();
        $filterModel->productId = $this->productId;

        $allModels = [];
        foreach ($filterModel->getQuery()->all() as $productStorageCell) {
            /**
             * @var ProductStorageCell $productStorageCell
             */

            $expirationDate = $productStorageCell->receiptProduct->expiration_date;
            $productionDate = $productStorageCell->receiptProduct->production_date;

            $expirationDateObj = new DateTimeImmutable($expirationDate);
            $productionDateObj = new DateTimeImmutable($productionDate);
            $today = new DateTimeImmutable();

            if ($today >= $expirationDateObj) {
                $expirationPercentage = 0;
            } else {
                $expirationPercentage = (int) (100 * ($expirationDateObj->diff($today)->days + 1) / $expirationDateObj->diff($productionDateObj)->days);
            }

            $allModels[] = [
                'amount' => $productStorageCell->amount,
                'expirationDate' => Yii::$app->formatter->asDate($expirationDate),
                'expirationPercentage' => $expirationPercentage,
                'productName' => $productStorageCell->receiptProduct->product->name,
                'productStorageCellId' => $productStorageCell->id,
                'productionDate' => Yii::$app->formatter->asDate($productionDate),
                'series' => $productStorageCell->receiptProduct->series,
                'storageCell' => $productStorageCell->storageCell->getName(),
            ];
        }

        usort($allModels, function ($a, $b) {
            if ($a['expirationPercentage'] != $b['expirationPercentage']) {
                return $a['expirationPercentage'] <=> $b['expirationPercentage'];
            }

            return $a['amount'] <=> $b['amount'];
        });

        return $this->render('products-storage-cells-widget', [
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $allModels,
                'pagination' => false,
            ]),
            'product' => $product,
        ]);
    }
}
