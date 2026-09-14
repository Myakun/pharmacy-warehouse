<?php

declare(strict_types=1);

namespace app\models\shipment;

use app\components\web\crud\Model;
use app\models\Customer;
use app\models\Shipment;
use DateTime;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property Shipment $entity
 */
class Save extends Model
{
    public ?string $customerId = null;

    public ?string $invoiceDate = null;

    public ?string $invoiceNumber = null;

    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($this->entity, $config);

        /**
         * @var Shipment $entity
         */

        if ($entity->getIsNewRecord()) {
            return;
        }

        $this->customerId = (string) $entity->customer_id;
        $this->invoiceDate = Yii::$app->formatter->asDate($entity->invoice_date);
        $this->invoiceNumber = (string) $entity->invoice_number;
    }


    #[ArrayShape(['customerId' => 'string', 'invoiceDate' => 'string', 'invoiceNumber' => 'string'])]
    public function attributeLabels(): array
    {
        $labels = new Shipment()->attributeLabels();

        return [
            'customerId' => $labels['customer_id'],
            'invoiceDate' => $labels['invoice_date'],
            'invoiceNumber' => $labels['invoice_number'],
        ];
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'customer_id' => $this->customerId,
            'invoice_date' => (DateTime::createFromFormat('d.m.Y', $this->invoiceDate))->format('Y-m-d'),
            'invoice_number' => $this->invoiceNumber,
        ]);
    }

    public function getCustomerIdValueText(): string
    {
        if (empty($this->customerId)) {
            return '';
        }

        $customer = Customer::findOne($this->customerId);

        return sprintf('%s (%s)', $customer->name, $customer->address);
    }

    public function rules(): array
    {
        return [
            ['customerId', 'required'],
            ['customerId', 'integer'],
            ['customerId', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Customer::class,
            ],

            ['invoiceDate', 'required'],
            ['invoiceDate', 'date', 'format' => 'php:d.m.Y'],

            ['invoiceNumber', 'required'],
            ['invoiceNumber', 'integer', 'min' => Shipment::INVOICE_NUMBER_MIN],
        ];
    }
}
