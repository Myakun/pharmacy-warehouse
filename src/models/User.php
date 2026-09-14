<?php

declare(strict_types=1);

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * @property User|null $createdBy
 * @property string $email
 * @property int $id
 * @property string $name
 * @property string $password
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const NAME_MAX_LENGTH = 100;

    public const PERMISSION_LIST = 'listUsers';

    public const PERMISSION_MANAGE = 'manageUsers';

    public const ROLE_ACCOUNTANT = 'Accountant';

    public const ROLE_GENERAL_DIRECTOR = 'General director';

    public const ROLE_ASSOCIATE_DIRECTOR = 'Associate director';

    public const ROLE_WAREHOUSE_MANAGER = 'Warehouse manager';

    public const ROLE_ORDER_COLLECTOR = 'Order collector';

    public const ROLE_MANAGER = 'Manager';

    public const UNIQUE_ROLES = [self::ROLE_ASSOCIATE_DIRECTOR, self::ROLE_GENERAL_DIRECTOR, self::ROLE_WAREHOUSE_MANAGER];

    #[ArrayShape(['name' => 'string'])]
    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя',
        ];
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert || $this->isAttributeChanged('password')) {
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }

        return true;
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

    public static function findIdentity($id): ?self
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        return null;
    }

    public function getAuthKey(): ?string
    {
        return null;
    }

    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

    public function rules(): array
    {
        return [
            ['email', 'email'],
            ['email', 'required'],
            ['email', 'unique'],

            ['name', 'required'],
            ['name', 'string', 'max' => self::NAME_MAX_LENGTH],

            ['password', 'required',
                'when' => function (self $user) {
                    return $user->getIsNewRecord();
                },
            ],
        ];
    }

    public static function tableName(): string
    {
        return 'users';
    }

    public function validateAuthKey($authKey): ?bool
    {
        return null;
    }
}
