<?php

declare(strict_types=1);

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property User $createdBy
 * @property int $id
 * @property int $packages_amount
 * @property int $shipment_id
 * @property ProductStorageCell $productStorageCell
 * @property int $product_storage_cell_id
 */
class ShipmentProduct extends ActiveRecord
{
    public const PACKAGES_AMOUNT_MIN = 1;

    public function afterDelete(): void
    {
        parent::afterDelete();

        $this->productStorageCell->amount += $this->packages_amount;
        $this->productStorageCell->out -= $this->packages_amount;
        $this->productStorageCell->save();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->productStorageCell->ship((int) $this->packages_amount);
    }

    public function attributeLabels(): array
    {
        return [
            'packages_amount' => 'Количество упаковок',
        ];
    }

    public function behaviors(): array
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getProductStorageCell(): ActiveQuery
    {
        return $this->hasOne(ProductStorageCell::class, ['id' => 'product_storage_cell_id']);
    }

    public function rules(): array
    {
        return [
            ['packages_amount', 'required'],
            ['packages_amount', 'integer', 'min' => self::PACKAGES_AMOUNT_MIN],

            ['product_storage_cell_id', 'required'],
            ['product_storage_cell_id', 'integer'],
            ['product_storage_cell_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => ProductStorageCell::class,
            ],

            ['shipment_id', 'required'],
            ['shipment_id', 'integer'],
            ['shipment_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Shipment::class,
            ],
        ];
    }

    public static function tableName(): string
    {
        return 'shipments_products';
    }
}
