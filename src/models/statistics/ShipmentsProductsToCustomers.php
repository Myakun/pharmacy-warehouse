<?php

declare(strict_types=1);

namespace app\models\statistics;

use app\models\Customer;
use app\models\Product;
use app\models\Shipment;
use DateTime;
use JetBrains\PhpStorm\ArrayShape;
use yii\base\Model;
use yii\db\ActiveQuery;

class ShipmentsProductsToCustomers extends Model
{
    public ?string $customerId = null;

    private bool $filterEnabled = false;

    public ?string $invoiceDateFrom = null;

    public ?string $invoiceDateTo = null;

    public ?string $productId = null;

    public ?string $series = null;

    #[ArrayShape([
        'customerId' => 'string',
        'invoiceDateFrom' => 'string',
        'invoiceNumber' => 'string',
        'productId' => 'string',
        'series' => 'string',
    ])]
    public function attributeLabels(): array
    {
        $labels = new Shipment()->attributeLabels();

        return [
            'customerId' => $labels['customer_id'],
            'invoiceDateFrom' => $labels['invoice_date'],
            'invoiceNumber' => $labels['invoice_number'],
            'productId' => 'Товар',
            'series' => 'Серия',
        ];
    }

    public function filterEnabled(): bool
    {
        return $this->filterEnabled;
    }

    public function getCustomerIdValueText(): string
    {
        if (empty($this->customerId)) {
            return '';
        }

        $customer = Customer::findOne($this->customerId);

        return sprintf('%s (%s)', $customer->name, $customer->address);
    }

    public function getProductIdValueText(): string
    {
        if (null == $this->productId) {
            return '';
        }

        return (Product::findOne($this->productId))->name;
    }

    public function getQuery(): ActiveQuery
    {
        $query = Shipment::find()
            ->with([
                'createdBy',
                'customer',
                'shipmentProducts' => function (ActiveQuery $query) {
                    $query->with(['productStorageCell' => function (ActiveQuery $query) {
                        $query->with(['receiptProduct' => function (ActiveQuery $query) {
                            $query->with(['product']);
                        }]);
                    }]);
                },
            ]);

        if (null != $this->customerId) {
            $this->filterEnabled = true;
            $query->andWhere(['customer_id' => $this->customerId]);
        }

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
                'shipmentProducts' => function (ActiveQuery $query) {
                    $query->innerJoinWith(['productStorageCell' => function (ActiveQuery $query) {
                        $query->innerJoinWith(['receiptProduct' => function (ActiveQuery $query) {
                            $query->andWhere(['product_id' => $this->productId]);
                        }]);
                    }]);
                },
            ]);
        }

        if (null != $this->series) {
            $this->filterEnabled = true;
            $query->innerJoinWith([
                'shipmentProducts' => function (ActiveQuery $query) {
                    $query->innerJoinWith(['productStorageCell' => function (ActiveQuery $query) {
                        $query->innerJoinWith(['receiptProduct' => function (ActiveQuery $query) {
                            $query->andWhere(['series' => $this->series]);
                        }]);
                    }]);
                },
            ]);
        }

        if (!$this->filterEnabled) {
            $query->andWhere('1 = 0');
        }

        return $query;
    }

    public function rules(): array
    {
        return [
            ['customerId', 'safe'],

            ['invoiceDateFrom', 'safe'],

            ['invoiceDateTo', 'safe'],

            ['productId', 'safe'],

            ['series', 'safe'],
        ];
    }
}
