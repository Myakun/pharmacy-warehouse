<?php

declare(strict_types=1);

use app\models\StorageCell;
use yii\db\Migration;

class m220503_182001_storage_cells extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable(StorageCell::tableName(), [
            'id' => $this->primaryKey(),
            'rack_number' => $this->integer()->notNull(),
            'row_number' => $this->integer()->notNull(),
            'shelf_number' => $this->string(1)->notNull(),
            'storage_mode_id' => $this->integer()->notNull(),
            'volume' => $this->integer()->notNull(),
            'volume_left' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'FOREIGN KEY (created_by) REFERENCES users(id)',
            'FOREIGN KEY (storage_mode_id) REFERENCES storage_modes(id)',
        ]);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(StorageCell::tableName());

        return true;
    }
}
