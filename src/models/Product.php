<?php

declare(strict_types=1);

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property User $createdBy
 * @property int $id
 * @property string $name
 * @property int $package_volume
 * @property Producer $producer
 * @property int $producer_id
 * @property ReceiptProduct[] $receiptsProducts
 * @property StorageMode $storageMode
 * @property int $storage_mode_id
 */
class Product extends ActiveRecord
{
    public const NAME_MAX_LENGTH = 255;

    public const PACKAGE_VOLUME_MIN = 1;

    public const PERMISSION_LIST = 'listProducts';

    public const PERMISSION_MANAGE = 'manageProducts';

    #[ArrayShape([
        'name' => 'string',
        'package_volume' => 'string',
        'producer_id' => 'string',
        'storage_mode_id' => 'string',
    ])]
    public function attributeLabels(): array
    {
        return [
            'name' => 'Наименование',
            'package_volume' => 'Объем упаковки (куб. ед.)',
            'producer_id' => 'Производитель',
            'storage_mode_id' => 'Условия хранения',
        ];
    }

    #[ArrayShape(['blameable' => 'array', 'timestamp' => 'array'])]
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

    public function getProducer(): ActiveQuery
    {
        return $this->hasOne(Producer::class, ['id' => 'producer_id']);
    }

    public function getReceiptsProducts(): ActiveQuery
    {
        return $this->hasMany(ReceiptProduct::class, ['product_id' => 'id']);
    }

    public function getStorageMode(): ActiveQuery
    {
        return $this->hasOne(StorageMode::class, ['id' => 'storage_mode_id']);
    }

    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => self::NAME_MAX_LENGTH],

            ['package_volume', 'required'],
            ['package_volume', 'integer', 'min' => self::PACKAGE_VOLUME_MIN],

            ['producer_id', 'required'],
            ['producer_id', 'integer'],
            ['producer_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Producer::class,
            ],
            ['producer_id', 'unique', 'targetAttribute' => ['name', 'producer_id']],

            ['storage_mode_id', 'required'],
            ['storage_mode_id', 'integer'],
            ['storage_mode_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => StorageMode::class,
            ],
        ];
    }

    public static function tableName(): string
    {
        return 'products';
    }
}
