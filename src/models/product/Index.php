<?php

declare(strict_types=1);

namespace app\models\product;

use app\models\Producer;
use app\models\Product;
use app\models\StorageMode;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class Index extends Model
{
    private bool $filterEnabled = false;

    public ?string $name = null;

    public ?string $producer = null;

    public ?int $storageModeId = null;

    public function attributeLabels(): array
    {
        $labels = new Product()->attributeLabels();

        return [
            'name' => $labels['name'],
            'producer' => $labels['producer_id'],
            'storageModeId' => $labels['storage_mode_id'],
        ];
    }

    public function filterEnabled(): bool
    {
        return $this->filterEnabled;
    }

    public function getStorageModeIdOptions(): array
    {
        $query = StorageMode::find()
            ->select(['id', 'name'])
            ->orderBy('name ASC')
            ->asArray();

        return ArrayHelper::map($query->all(), 'id', 'name');
    }

    public function getQuery(): ActiveQuery
    {
        $producersTableName = Producer::tableName();
        $productsTableName = Product::tableName();

        $query = Product::find()
            ->innerJoinWith(['createdBy', 'producer', 'storageMode'])
            ->with(['receiptsProducts']);

        if (null != $this->name) {
            $this->filterEnabled = true;
            $query->andFilterWhere(['like', "$productsTableName.name", $this->name]);
        }

        if (null != $this->producer) {
            $this->filterEnabled = true;
            $query->andWhere(["lower($producersTableName.name)" => mb_strtolower($this->producer)]);
        }

        if (null != $this->storageModeId) {
            $this->filterEnabled = true;
            $query->andWhere(["$productsTableName.storage_mode_id" => $this->storageModeId]);
        }

        return $query;
    }

    public function rules(): array
    {
        return [
            ['name', 'safe'],

            ['producer', 'safe'],

            ['storageModeId', 'safe'],
        ];
    }
}
