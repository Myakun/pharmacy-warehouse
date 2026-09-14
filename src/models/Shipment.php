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
 * @property Customer $customer
 * @property int $customer_id
 * @property int $id
 * @property string $invoice_date
 * @property int $invoice_number
 * @property ShipmentProduct $shipmentProducts
 */
class Shipment extends ActiveRecord
{
    public const INVOICE_NUMBER_MIN = 1;

    public const INVOICE_NUMBER_PREFIX = 'ОН-';

    public const PERMISSION_LIST = 'listShipments';

    public const PERMISSION_MANAGE = 'manageShipments';

    #[ArrayShape(['customer_id' => 'string', 'invoice_date' => 'string', 'invoice_number' => 'string'])]
    public function attributeLabels(): array
    {
        return [
            'customer_id' => 'Клиент',
            'invoice_date' => 'Дата накладной',
            'invoice_number' => 'Номер накладной',
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

    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    public function getShipmentProducts(): ActiveQuery
    {
        return $this->hasMany(ShipmentProduct::class, ['shipment_id' => 'id']);
    }

    public function rules(): array
    {
        return [
            ['customer_id', 'required'],
            ['customer_id', 'integer'],
            ['customer_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Customer::class,
            ],

            ['invoice_date', 'required'],
            ['invoice_date', 'date', 'format' => 'php:Y-m-d'],

            ['invoice_number', 'required'],
            ['invoice_number', 'integer', 'min' => self::INVOICE_NUMBER_MIN],
        ];
    }

    public static function tableName(): string
    {
        return 'shipments';
    }
}
