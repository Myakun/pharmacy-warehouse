<?php

declare(strict_types=1);

namespace app\models\receipt_product;

use app\components\web\crud\Model;
use app\models\Product;
use app\models\ReceiptProduct;
use DateTime;
use Yii;

/**
 * @property ReceiptProduct $entity
 */
class Save extends Model
{
    public ?string $expirationDate = null;

    public ?string $packagesAmount = null;

    public ?string $productId = null;

    public ?string $productionDate = null;

    public ?string $series = null;

    public ?array $storageCells = null;

    public function attributeLabels(): array
    {
        $labels = new ReceiptProduct()->attributeLabels();

        return [
            'expirationDate' => $labels['expiration_date'],
            'packagesAmount' => $labels['packages_amount'],
            'productId' => $labels['product_id'],
            'productionDate' => $labels['production_date'],
            'series' => $labels['series'],
        ];
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'expiration_date' => (DateTime::createFromFormat('d.m.Y', $this->expirationDate))->format('Y-m-d'),
            'packages_amount' => $this->packagesAmount,
            'product_id' => $this->productId,
            'production_date' => (DateTime::createFromFormat('d.m.Y', $this->productionDate))->format('Y-m-d'),
            'series' => $this->series,
        ]);
    }

    public function getProductIdValueText(): string
    {
        if (empty($this->productId)) {
            return '';
        }

        return Product::findOne($this->productId)->name;
    }

    public function rules(): array
    {
        return [
            ['expirationDate', 'required'],
            ['expirationDate', 'date', 'format' => 'php:d.m.Y'],

            ['packagesAmount', 'required'],
            ['packagesAmount', 'integer', 'min' => ReceiptProduct::PACKAGES_AMOUNT_MIN],

            ['productId', 'required'],
            ['productId', 'integer'],
            ['productId', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Product::class,
            ],

            ['productionDate', 'required'],
            ['productionDate', 'date', 'format' => 'php:d.m.Y'],

            ['series', 'required'],
            ['series', 'string', 'max' => ReceiptProduct::SERIES_MAX_LENGTH],

            ['storageCells', 'storageCellsRule', 'skipOnEmpty' => false],
        ];
    }

    public function storageCellsRule(): void
    {
        if (empty($this->storageCells)) {
            $this->addError('storageCells', Yii::t('app', 'Select storage cells'));
            return;
        }

        if (array_sum($this->storageCells) < $this->packagesAmount) {
            $this->addError('storageCells', Yii::t('app', 'Place all packages into cells'));
        }
    }
}
