<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property User $createdBy
 * @property int $id
 * @property string $name
 * @property Product[] $products
 */
class Producer extends ActiveRecord
{
    public const NAME_MAX_LENGTH = 100;

    public const PERMISSION_LIST = 'listProducers';

    public const PERMISSION_MANAGE = 'manageProducers';

    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('app', 'Name'),
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

    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['producer_id' => 'id']);
    }

    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => self::NAME_MAX_LENGTH],
            ['name', 'unique'],
        ];
    }

    public static function tableName(): string
    {
        return 'producers';
    }
}
