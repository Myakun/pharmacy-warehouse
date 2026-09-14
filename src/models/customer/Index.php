<?php

declare(strict_types=1);

namespace app\models\customer;

use app\models\Customer;
use yii\base\Model;
use yii\db\ActiveQuery;

class Index extends Model
{
    public ?string $contractNumber = null;

    public ?string $name = null;

    public function getQuery(): ActiveQuery
    {
        $query = Customer::find()
            ->with(['createdBy', 'shipments']);

        if (null != $this->contractNumber) {
            $query
                ->andWhere(['like', 'contract_number', str_replace(Customer::CONTRACT_NUMBER_PREFIX, '', $this->contractNumber)]);
        }

        if (null != $this->name) {
            $query->andWhere(
                ['or',
                    ['like', 'address', $this->name],
                    ['like', 'contact_person', $this->name],
                    ['like', 'name', $this->name],
                    ['like', 'phone', $this->name],
                ]
            );
        }

        return $query;
    }

    public function rules(): array
    {
        return [
            ['contractNumber', 'safe'],

            ['name', 'safe'],
        ];
    }
}
