<?php

declare(strict_types=1);

namespace app\models\producer;

use app\models\Producer;
use yii\base\Model;
use yii\db\ActiveQuery;

class Index extends Model
{
    public ?string $name = null;

    public function getQuery(): ActiveQuery
    {
        $query = Producer::find()
            ->select('*, (SELECT COUNT(*) FROM products WHERE producer_id = producers.id) as products_count')
            ->with(['createdBy'])
            ->orderBy('name ASC');

        if (null != $this->name) {
            $query->andFilterWhere(['like', 'name', $this->name]);
        }

        return $query;
    }

    public function rules(): array
    {
        return [
            ['name', 'safe'],
        ];
    }
}
