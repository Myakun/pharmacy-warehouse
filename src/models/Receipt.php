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
 * @property string $invoice_date
 * @property int $invoice_number
 * @property ReceiptProduct[] $receiptProducts
 * @property Supplier $supplier
 * @property int $supplier_id
 */
class Receipt extends ActiveRecord
{
    public const INVOICE_NUMBER_MIN = 1;

    public const INVOICE_NUMBER_PREFIX = 'ПН-';

    public const PERMISSION_LIST = 'listReceipts';

    public const PERMISSION_MANAGE = 'manageReceipts';

    #[ArrayShape(['invoice_date' => 'string', 'invoice_number' => 'string', 'supplier_id' => 'string'])]
    public function attributeLabels(): array
    {
        return [
            'invoice_date' => 'Дата накладной',
            'invoice_number' => 'Номер накладной',
            'supplier_id' => 'Поставщик',
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

    public function getReceiptProducts(): ActiveQuery
    {
        return $this->hasMany(ReceiptProduct::class, ['receipt_id' => 'id']);
    }

    public function getSupplier(): ActiveQuery
    {
        return $this->hasOne(Supplier::class, ['id' => 'supplier_id']);
    }

    public function rules(): array
    {
        return [
            ['invoice_date', 'required'],
            ['invoice_date', 'date', 'format' => 'php:Y-m-d'],

            ['invoice_number', 'required'],
            ['invoice_number', 'integer', 'min' => self::INVOICE_NUMBER_MIN],

            ['supplier_id', 'required'],
            ['supplier_id', 'integer'],
            ['supplier_id', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Supplier::class,
            ],
        ];
    }

    public static function tableName(): string
    {
        return 'receipts';
    }
}
