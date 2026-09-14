<?php

declare(strict_types=1);

namespace app\models\receipt;

use app\components\web\crud\Model;
use app\models\Receipt;
use app\models\Supplier;
use DateTime;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property Receipt $entity
 */
class Save extends Model
{
    public ?string $invoiceDate = null;

    public ?string $invoiceNumber = null;

    public ?string $supplierId = null;

    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($this->entity, $config);

        /**
         * @var Receipt $entity
         */

        if ($entity->getIsNewRecord()) {
            return;
        }

        $this->invoiceDate = Yii::$app->formatter->asDate($entity->invoice_date);
        $this->invoiceNumber = (string) $entity->invoice_number;
        $this->supplierId = (string) $entity->supplier_id;
    }


    #[ArrayShape(['invoiceDate' => 'string', 'invoiceNumber' => 'string', 'supplierId' => 'string'])]
    public function attributeLabels(): array
    {
        $labels = new Receipt()->attributeLabels();

        return [
            'invoiceDate' => $labels['invoice_date'],
            'invoiceNumber' => $labels['invoice_number'],
            'supplierId' => $labels['supplier_id'],
        ];
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'invoice_date' => (DateTime::createFromFormat('d.m.Y', $this->invoiceDate))->format('Y-m-d'),
            'invoice_number' => $this->invoiceNumber,
            'supplier_id' => $this->supplierId,
        ]);
    }

    public function getSupplierIdValueText(): string
    {
        if (empty($this->supplierId)) {
            return '';
        }

        $supplier = Supplier::findOne($this->supplierId);

        return sprintf('%s (%s)', $supplier->name, $supplier->address);
    }

    public function rules(): array
    {
        return [
            ['invoiceDate', 'required'],
            ['invoiceDate', 'date', 'format' => 'php:d.m.Y'],

            ['invoiceNumber', 'required'],
            ['invoiceNumber', 'integer', 'min' => Receipt::INVOICE_NUMBER_MIN],

            ['supplierId', 'required'],
            ['supplierId', 'integer'],
            ['supplierId', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Supplier::class,
            ],
        ];
    }
}
