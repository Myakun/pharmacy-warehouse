<?php

declare(strict_types=1);

namespace app\models\customer;

use app\components\web\crud\Model;
use app\models\Customer;
use DateTime;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property Customer $entity
 */
class Save extends Model
{
    public ?string $address = null;

    public ?string $contactPerson = null;

    public ?string $contractDate = null;

    public ?string $contractNumber = null;

    public ?string $name = null;

    public ?string $phone = null;

    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($this->entity, $config);

        /**
         * @var Customer $entity
         */

        if ($entity->getIsNewRecord()) {
            return;
        }

        $this->address = $entity->address;
        $this->contactPerson = $entity->contact_person;
        $this->contractDate = Yii::$app->formatter->asDate($entity->contract_date);
        $this->contractNumber = (string) $entity->contract_number;
        $this->name = $entity->name;
        $this->phone = $entity->getPhoneFormatted();
    }

    public function attributeLabels(): array
    {
        $labels = new Customer()->attributeLabels();

        return [
            'address' => $labels['address'],
            'contactPerson' => $labels['contact_person'],
            'contractDate' => $labels['contract_date'],
            'contractNumber' => $labels['contract_number'],
            'name' => $labels['name'],
            'phone' => $labels['phone'],
        ];
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'address' => $this->address,
            'contact_person' => $this->contactPerson,
            'contract_date' => (DateTime::createFromFormat('d.m.Y', $this->contractDate))->format('Y-m-d'),
            'contract_number' => $this->contractNumber,
            'name' => $this->name,
            'phone' => $this->phone,
        ]);
    }

    public function rules(): array
    {
        return [
            ['address', 'required'],
            ['address', 'string', 'max' => Customer::ADDRESS_MAX_LENGTH],

            ['contactPerson', 'required'],
            ['contactPerson', 'string', 'max' => Customer::CONTACT_PERSON_MAX_LENGTH],

            ['contractDate', 'required'],
            ['contractDate', 'date', 'format' => 'php:d.m.Y'],

            ['contractNumber', 'required'],
            ['contractNumber', 'integer', 'min' => Customer::CONTRACT_NUMBER_MIN],

            ['name', 'required'],
            ['name', 'string', 'max' => Customer::NAME_MAX_LENGTH],

            ['phone', 'filter', 'filter' => function () {
                return str_replace([' ', '+7', '-', '(', ')'], '', $this->phone);
            }],
            ['phone', 'required'],
            ['phone', 'string', 'length' => Customer::PHONE_LENGTH],
        ];
    }
}
