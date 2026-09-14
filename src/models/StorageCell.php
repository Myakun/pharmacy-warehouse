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
 * @property ProductStorageCell[] $productsStorageCells
 * @property string $shelf_number
 * @property int $rack_number
 * @property int $row_number
 * @property StorageMode $storageMode
 * @property int $storage_mode_id
 * @property int $volume
 * @property int $volume_left
 */
class StorageCell extends ActiveRecord
{
    public const PERMISSION_LIST = 'listStorageCells';

    public const PERMISSION_MANAGE = 'manageStorageCells';

    public const RACK_NUMBER_MAX = 12;

    public const RACK_NUMBER_MIN = 1;

    public const ROW_NUMBER_MAX = 10;

    public const ROW_NUMBER_MIN = 1;

    public const VOLUME_MIN = 1;

    public function attributeLabels(): array
    {
        return [
            'shelf_number' => 'Полка',
            'rack_number' => 'Стеллаж',
            'row_number' => 'Ряд',
            'storage_mode_id' => 'Условия хранения',
            'volume' => 'Общий объем (куб. ед.)',
            'volume_left' => 'Остаток объема (куб. ед.)',
        ];
    }

    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        if ($this->getIsNewRecord()) {
            $this->volume_left = $this->volume;
        }

        return true;
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

    public function getName(): string
    {
        $rackNumber = (string) $this->rack_number;
        if ($this->rack_number < 10) {
            $rackNumber = '0' . $rackNumber;
        }

        $rowNumber = (string) $this->row_number;
        if (in_array($this->storageMode->name, ['СД', 'СК'])) {
            $rowNumber = $this->storageMode->name;
        } elseif ($this->storageMode->name == 'Холодильник') {
            $rowNumber = 'Х';
        }

        return sprintf('%s-%s-%s', $rowNumber, $rackNumber, $this->shelf_number);
    }

    public function getRowName(): string
    {
        if (in_array($this->storageMode->name, ['СД', 'СК', 'Холодильник'])) {
            return $this->storageMode->name;
        }

        return 'Ряд ' . $this->row_number;
    }

    public function getProductsStorageCells(): ActiveQuery
    {
        return $this->hasMany(ProductStorageCell::class, ['storage_cell_id' => 'id']);
    }

    public static function getShelfNumberOptions(): array
    {
        return [
            'А' => 'А',
            'Б' => 'Б',
            'В' => 'В',
            'Г' => 'Г',
        ];
    }

    public function getStorageMode(): ActiveQuery
    {
        return $this->hasOne(StorageMode::class, ['id' => 'storage_mode_id']);
    }

    public function rules(): array
    {
        return [
            ['rack_number', 'required'],
            ['rack_number', 'integer', 'min' => self::RACK_NUMBER_MIN, 'max' => self::RACK_NUMBER_MAX],

            ['row_number', 'required'],
            ['row_number', 'integer', 'min' => self::ROW_NUMBER_MIN, 'max' => self::ROW_NUMBER_MAX],

            ['shelf_number', 'required'],
            ['shelf_number', 'in', 'range' => $this->getShelfNumberOptions()],

            ['storage_mode_id', 'required'],
            ['storage_mode_id', 'integer'],
            ['storage_mode_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => StorageMode::class,
            ],

            ['volume', 'required'],
            ['volume', 'integer', 'min' => self::VOLUME_MIN],

            ['volume_left', 'required'],
            ['volume_left', 'integer', 'min' => 0],
        ];
    }

    public static function tableName(): string
    {
        return 'storage_cells';
    }
}
