<?php

declare(strict_types=1);

namespace app\models\storage_mode;

use app\models\StorageMode;
use yii\base\Model;
use yii\db\ActiveQuery;

class Index extends Model
{
    public ?string $name = null;

    public function getQuery(): ActiveQuery
    {
        $query = StorageMode::find()
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
