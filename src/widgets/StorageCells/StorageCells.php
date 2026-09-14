<?php

declare(strict_types=1);

namespace app\widgets\StorageCells;

use app\models\Product;
use app\models\ProductStorageCell;
use app\models\StorageCell;
use yii\base\Widget;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class StorageCells extends Widget
{
    public int $packagesAmount;

    public int $productId;

    public array $selectedStorageCells = [];

    public string $series;

    public function run(): string
    {
        $product = Product::findOne($this->productId);
        if (null == $product) {
            throw new NotFoundHttpException();
        }

        // region Cells with another packages

        $cellsWithAnotherPackages = [];

        $symbol = '{NUM}';
        $name = preg_replace('/\d/', $symbol, $product->name);
        if (str_contains($name, $symbol)) {
            $parts = explode($symbol, $name);
            $products = Product::find()
                ->select(['id'])
                ->andWhere(['like', 'name', $parts[0] . '%', false])
                ->andWhere(['!= ', 'id', $product->id])
                ->all();

            if (!empty($products)) {
                $cellsWithAnotherPackages = ProductStorageCell::find()
                    ->select(['storage_cell_id'])
                    ->innerJoinWith([
                        'receiptProduct' => function (ActiveQuery $query) use ($products) {
                            $query->andWhere(['product_id' => ArrayHelper::getColumn($products, 'id')]);
                        },
                    ])
                    ->andWhere('amount > 0')
                    ->asArray()
                    ->all();

                if (!empty($cellsWithAnotherPackages)) {
                    $cellsWithAnotherPackages = ArrayHelper::getColumn($cellsWithAnotherPackages, 'storage_cell_id');
                    $cellsWithAnotherPackages = array_flip($cellsWithAnotherPackages);
                }
            }
        }

        // endregion

        // region Cells with another series

        $cellsWithAnotherSeries = ProductStorageCell::find()
            ->select(['storage_cell_id'])
            ->innerJoinWith([
                'receiptProduct' => function (ActiveQuery $query) {
                    $query
                        ->andWhere(['product_id' => $this->productId])
                        ->andWhere(['!=', 'series', $this->series]);
                },
            ])
            ->andWhere('amount > 0')
            ->asArray()
            ->all();

        if (!empty($cellsWithAnotherSeries)) {
            $cellsWithAnotherSeries = ArrayHelper::getColumn($cellsWithAnotherSeries, 'storage_cell_id');
            $cellsWithAnotherSeries = array_flip($cellsWithAnotherSeries);
        }

        // endregion

        $query = StorageCell::find()
            ->andWhere(['storage_mode_id' => $product->storage_mode_id]);

        $rows = [];
        foreach ($query->all() as $storageCell) {
            /**
             * @var StorageCell $storageCell
             */
            if (!isset($rows[$storageCell->row_number])) {
                $rows[$storageCell->row_number] = [
                    'name' => $storageCell->getRowName(),
                    'racks' => [],
                ];
            }

            $racks = &$rows[$storageCell->row_number]['racks'];

            if (!isset($racks[$storageCell->rack_number])) {
                $racks[$storageCell->rack_number] = [
                    'shelfs' => [],
                ];
            }

            $shelfs = &$racks[$storageCell->rack_number]['shelfs'];
            $shelfs[$storageCell->shelf_number] = [
                'anotherPackages' => isset($cellsWithAnotherPackages[$storageCell->id]),
                'anotherSeries' => isset($cellsWithAnotherSeries[$storageCell->id]),
                'id' => $storageCell->id,
                'maxPackages' => floor($storageCell->volume_left / $product->package_volume),
                'name' => $storageCell->getName(),
                'selected' => isset($this->selectedStorageCells[$storageCell->id]),
                'selectedAmount' => $this->selectedStorageCells[$storageCell->id] ?? 0,
            ];
        }

        return $this->render('storage-cells-widget', [
            'packagesAmount' => $this->packagesAmount,
            'product' => $product,
            'rows' => $rows,
        ]);
    }
}
