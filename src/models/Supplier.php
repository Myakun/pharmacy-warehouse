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
 * @property string $address
 * @property string $contact_person
 * @property string $contract_date
 * @property int $contract_number
 * @property User $createdBy
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property Receipt[] $receipts
 */
class Supplier extends ActiveRecord
{
    public const ADDRESS_MAX_LENGTH = 255;

    public const CONTACT_PERSON_MAX_LENGTH = 100;

    public const CONTRACT_NUMBER_MIN = 1;

    public const CONTRACT_NUMBER_PREFIX = 'П-';

    public const NAME_MAX_LENGTH = 150;

    public const PERMISSION_LIST = 'listSuppliers';

    public const PERMISSION_MANAGE = 'manageSuppliers';

    public const PHONE_LENGTH = 10;

    #[ArrayShape([
        'address' => 'string',
        'contact_person' => 'string',
        'contract_date' => 'string',
        'contract_number' => 'string',
        'name' => 'string',
        'phone' => 'string',
    ])]
    public function attributeLabels(): array
    {
        return [
            'address' => 'Адрес',
            'contact_person' => 'Контактное лицо',
            'contract_date' => 'Дата договора',
            'contract_number' => 'Номер договора',
            'name' => 'Поставщик',
            'phone' => 'Телефон',
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

    public function getPhoneFormatted(): string
    {
        return preg_replace('/^(\d{3})(\d{3})(\d{2})(\d{2})$/', '+7 ($1) $2-$3-$4', $this->phone);
    }

    public function getReceipts(): ActiveQuery
    {
        return $this->hasMany(Receipt::class, ['supplier_id' => 'id']);
    }

    public function rules(): array
    {
        return [
            ['address', 'required'],
            ['address', 'string', 'max' => self::ADDRESS_MAX_LENGTH],

            ['contact_person', 'required'],
            ['contact_person', 'string', 'max' => self::CONTACT_PERSON_MAX_LENGTH],

            ['contract_date', 'required'],
            ['contract_date', 'date', 'format' => 'php:Y-m-d'],

            ['contract_number', 'required'],
            ['contract_number', 'integer', 'min' => self::CONTRACT_NUMBER_MIN],
            ['contract_number', 'unique'],

            ['name', 'required'],
            ['name', 'string', 'max' => self::NAME_MAX_LENGTH],
            ['name', 'unique', 'targetAttribute' => ['address', 'name']],

            ['phone', 'required'],
            ['phone', 'string', 'length' => self::PHONE_LENGTH],
            ['phone', 'unique'],
        ];
    }

    public static function tableName(): string
    {
        return 'suppliers';
    }
}
