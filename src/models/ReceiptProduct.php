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
 * @property string $expiration_date
 * @property int $id
 * @property int $packages_amount
 * @property Product $product
 * @property int $product_id
 * @property string $production_date
 * @property int $receipt_id
 * @property string $series
 * @property ProductStorageCell[] $productsStorageCells
 * @property int $storage_cell_id
 */
class ReceiptProduct extends ActiveRecord
{
    public const PACKAGES_AMOUNT_MIN = 1;

    public const SERIES_MAX_LENGTH = 20;

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        foreach ($this->productsStorageCells as $productStorageCell) {
            $productStorageCell->delete();
        }

        return true;
    }

    public function attributeLabels(): array
    {
        return [
            'expiration_date' => 'Срок годности',
            'packages_amount' => 'Количество упаковок',
            'product_id' => 'Товар',
            'production_date' => 'Дата производства',
            'series' => 'Серия',
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

    public function getProductsStorageCells(): ActiveQuery
    {
        return $this->hasMany(ProductStorageCell::class, ['receipt_product_id' => 'id']);
    }

    public function rules(): array
    {
        return [
            ['expiration_date', 'required'],
            ['expiration_date', 'date', 'format' => 'php:Y-m-d'],

            ['packages_amount', 'required'],
            ['packages_amount', 'integer', 'min' => self::PACKAGES_AMOUNT_MIN],

            ['product_id', 'required'],
            ['product_id', 'integer'],
            ['product_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Product::class,
            ],

            ['production_date', 'required'],
            ['production_date', 'date', 'format' => 'php:Y-m-d'],

            ['receipt_id', 'required'],
            ['receipt_id', 'integer'],
            ['receipt_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Receipt::class,
            ],

            ['series', 'required'],
            ['series', 'string', 'max' => self::SERIES_MAX_LENGTH],
        ];
    }

    public static function tableName(): string
    {
        return 'receipts_products';
    }
}
