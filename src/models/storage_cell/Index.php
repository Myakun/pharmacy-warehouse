<?php

declare(strict_types=1);

namespace app\models\storage_cell;

use app\models\StorageCell;
use yii\base\Model;
use yii\db\ActiveQuery;

class Index extends Model
{
    public function getQuery(): ActiveQuery
    {
        $query = StorageCell::find()
            ->with(['createdBy', 'productsStorageCells', 'storageMode']);

        return $query;
    }
}
