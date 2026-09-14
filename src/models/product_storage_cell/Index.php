<?php

declare(strict_types=1);

namespace app\models\product_storage_cell;

use app\models\Product;
use app\models\ProductStorageCell;
use JetBrains\PhpStorm\ArrayShape;
use yii\base\Model;
use yii\db\ActiveQuery;

class Index extends Model
{
    private bool $filterEnabled = false;

    public ?string $productId = null;

    public ?string $series = null;

    #[ArrayShape(['productId' => 'string', 'series' => 'string'])]
    public function attributeLabels(): array
    {
        return [
            'productId' => 'Товар',
            'series' => 'Серия',
        ];
    }

    public function filterEnabled(): bool
    {
        return $this->filterEnabled;
    }

    public function getProductIdValueText(): string
    {
        if (null == $this->productId) {
            return '';
        }

        return (Product::findOne($this->productId))->name;
    }

    public function getQuery(): ActiveQuery
    {
        $productStorageCellsTableName = ProductStorageCell::tableName();

        $query = ProductStorageCell::find()
           ->andWhere("$productStorageCellsTableName.amount > 0")
           ->with([
               'receiptProduct' => function (ActiveQuery $query) {
                   $query->with(['product']);
               },
               'storageCell' => function (ActiveQuery $query) {
                   $query->with(['storageMode']);
               },
           ]);

        if (null != $this->productId) {
            $this->filterEnabled = true;
            $query->innerJoinWith([
                'receiptProduct' => function (ActiveQuery $query) {
                    $query->andWhere(['product_id' => $this->productId]);
                },
            ]);
        }

        if (null != $this->series) {
            $this->filterEnabled = true;
            $query->innerJoinWith([
                'receiptProduct' => function (ActiveQuery $query) {
                    $query->andWhere(['series' => $this->series]);
                },
            ]);
        }

        return $query;
    }

    public function rules(): array
    {
        return [
            ['productId', 'safe'],

            ['series', 'safe'],
        ];
    }
}
