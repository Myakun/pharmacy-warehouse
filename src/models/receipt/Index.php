<?php

declare(strict_types=1);

namespace app\models\receipt;

use app\models\Receipt;
use app\models\Supplier;
use DateTime;
use yii\base\Model;
use yii\db\ActiveQuery;

class Index extends Model
{
    private bool $filterEnabled = false;

    public ?string $invoiceDateFrom = null;

    public ?string $invoiceDateTo = null;

    public ?string $invoiceNumber = null;

    public ?string $supplierId = null;

    public function attributeLabels(): array
    {
        $labels = new Receipt()->attributeLabels();

        return [
            'invoiceDateFrom' => $labels['invoice_date'],
            'invoiceNumber' => $labels['invoice_number'],
            'supplierId' => $labels['supplier_id'],
        ];
    }

    public function filterEnabled(): bool
    {
        return $this->filterEnabled;
    }

    public function getSupplierIdValueText(): string
    {
        if (empty($this->supplierId)) {
            return '';
        }

        $supplier = Supplier::findOne($this->supplierId);

        return sprintf('%s (%s)', $supplier->name, $supplier->address);
    }

    public function getQuery(): ActiveQuery
    {
        $query = Receipt::find()
            ->with(['createdBy']);

        if (null != $this->invoiceDateFrom) {
            $date = DateTime::createFromFormat('d.m.Y', $this->invoiceDateFrom);
            if ($date) {
                $this->filterEnabled = true;
                $query->andWhere(['>=', 'invoice_date', $date->format('Y-m-d')]);
            }
        }

        if (null != $this->invoiceDateTo) {
            $date = DateTime::createFromFormat('d.m.Y', $this->invoiceDateTo);
            if ($date) {
                $this->filterEnabled = true;
                $query->andWhere(['<=', 'invoice_date', $date->format('Y-m-d')]);
            }
        }

        if (null != $this->invoiceNumber) {
            $this->filterEnabled = true;
            $query->andWhere(['invoice_number' => $this->invoiceNumber]);
        }

        if (null != $this->supplierId) {
            $this->filterEnabled = true;
            $query->andWhere(['supplier_id' => $this->supplierId]);
        }

        return $query;
    }

    public function rules(): array
    {
        return [
            ['invoiceDateFrom', 'safe'],

            ['invoiceDateTo', 'safe'],

            ['invoiceNumber', 'safe'],

            ['supplierId', 'safe'],
        ];
    }
}
