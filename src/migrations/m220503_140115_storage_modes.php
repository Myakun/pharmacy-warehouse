<?php

declare(strict_types=1);

namespace app\migrations;

use app\models\StorageMode;
use yii\db\Migration;

class m220503_140115_storage_modes extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable(StorageMode::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(StorageMode::NAME_MAX_LENGTH)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'FOREIGN KEY (created_by) REFERENCES users(id)',
        ]);

        $modes = ['Не выше 25°С', 'СД', 'СК', 'Холодильник'];
        foreach ($modes as $mode) {
            $storageMode = new StorageMode();
            $storageMode->getBehavior('blameable')->value = 1;
            $storageMode->name = $mode;
            $storageMode->save();
        }

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(StorageMode::tableName());

        return true;
    }
}
