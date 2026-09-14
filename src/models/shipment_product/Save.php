<?php

declare(strict_types=1);

namespace app\models\shipment_product;

use app\models\Product;
use yii\base\Model;

class Save extends Model
{
    public ?array $amounts;

    public ?string $productId = null;

    public function attributeLabels(): array
    {
        return [
            'productId' => 'Товар',
        ];
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
            ['amounts', 'amountsRule', 'skipOnEmpty' => false],

            ['productId', 'required'],
            ['productId', 'integer'],
            ['productId', 'exist',
                'targetAttribute' => 'id',
                'targetClass' => Product::class,
            ],
        ];
    }

    public function amountsRule(): void
    {
        if (array_sum($this->amounts) == 0) {
            $this->addError('amounts', 'Необходимо выбрать хотя бы одну упаковку');
        }
    }
}
