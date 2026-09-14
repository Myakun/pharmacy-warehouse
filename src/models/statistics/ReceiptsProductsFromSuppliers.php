<?php

declare(strict_types=1);

namespace app\models\statistics;

use app\models\Product;
use app\models\Receipt;
use app\models\Supplier;
use DateTime;
use yii\base\Model;
use yii\db\ActiveQuery;

class ReceiptsProductsFromSuppliers extends Model
{
    private bool $filterEnabled = false;

    public ?string $invoiceDateFrom = null;

    public ?string $invoiceDateTo = null;

    public ?string $productId = null;

    public ?string $series = null;

    public ?string $supplierId = null;

    public function attributeLabels(): array
    {
        $labels = new Receipt()->attributeLabels();

        return [
            'invoiceDateFrom' => $labels['invoice_date'],
            'invoiceNumber' => $labels['invoice_number'],
            'productId' => 'Товар',
            'series' => 'Серия',
            'supplierId' => 'Поставщик',
        ];
    }

    public function filterEnabled(): bool
    {
        return $this->filterEnabled;
    }

    public function getProductIdValueText(): string
    {
        if (null == $this->productId) {
            return '';
        }

        return (Product::findOne($this->productId))->name;
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
            ->with([
                'createdBy',
                'receiptProducts' => function (ActiveQuery $query) {
                    $query->with(['product']);
                },
                'supplier',
            ]);

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

        if (null != $this->productId) {
            $this->filterEnabled = true;
            $query->innerJoinWith([
                'receiptProducts' => function (ActiveQuery $query) {
                    $query->andWhere(['product_id' => $this->productId]);
                },
            ]);
        }

        if (null != $this->series) {
            $this->filterEnabled = true;
            $query->innerJoinWith([
                'receiptProducts' => function (ActiveQuery $query) {
                    $query->andWhere(['series' => $this->series]);
                },
            ]);
        }

        if (null != $this->supplierId) {
            $this->filterEnabled = true;
            $query->andWhere(['supplier_id' => $this->supplierId]);
        }

        if (!$this->filterEnabled) {
            $query->andWhere('1 = 0');
        }

        return $query;
    }

    public function rules(): array
    {
        return [
            ['invoiceDateFrom', 'safe'],

            ['invoiceDateTo', 'safe'],

            ['productId', 'safe'],

            ['series', 'safe'],

            ['supplierId', 'safe'],
        ];
    }
}
