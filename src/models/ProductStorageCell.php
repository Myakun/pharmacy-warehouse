<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $amount
 * @property int $id
 * @property int $in
 * @property int $out
 * @property ReceiptProduct $receiptProduct
 * @property int $receipt_product_id
 * @property StorageCell $storageCell
 * @property int $storage_cell_id
 * @property int $volume
 */
class ProductStorageCell extends ActiveRecord
{
    public const PERMISSION_LIST = 'listProductStorageCells';

    public function afterDelete(): void
    {
        parent::afterDelete();

        $this->storageCell->volume_left += $this->in * $this->receiptProduct->product->package_volume;
        $this->storageCell->save();
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert) {
            return;
        }

        $this->storageCell->volume_left -= $this->in * $this->receiptProduct->product->package_volume;
        $this->storageCell->save();
    }

    public function getReceiptProduct(): ActiveQuery
    {
        return $this->hasOne(ReceiptProduct::class, ['id' => 'receipt_product_id']);
    }

    public function getStorageCell(): ActiveQuery
    {
        return $this->hasOne(StorageCell::class, ['id' => 'storage_cell_id']);
    }

    public function rules(): array
    {
        return [
            ['amount', 'required'],
            ['amount', 'integer'],

            ['in', 'required'],
            ['in', 'integer'],

            ['out', 'integer'],
            ['out', 'default', 'value' => 0],

            ['receipt_product_id', 'required'],
            ['receipt_product_id', 'integer'],
            ['receipt_product_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => ReceiptProduct::class,
            ],

            ['storage_cell_id', 'required'],
            ['storage_cell_id', 'integer'],
            ['storage_cell_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => StorageCell::class,
            ],
        ];
    }

    public function ship(int $amount)
    {
        $this->amount -= $amount;
        $this->out += $amount;
        $this->save();

        $this->storageCell->volume_left += $amount * $this->receiptProduct->product->package_volume;
        $this->storageCell->save();
    }

    public static function tableName(): string
    {
        return 'products_storage_cells';
    }
}
