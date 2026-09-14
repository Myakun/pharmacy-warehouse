<?php

declare(strict_types=1);

use app\models\Product;
use yii\db\Migration;

class m220503_163021_products extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable(Product::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(Product::NAME_MAX_LENGTH)->notNull(),
            'package_volume' => $this->integer()->notNull(),
            'producer_id' => $this->integer()->notNull(),
            'storage_mode_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'FOREIGN KEY (created_by) REFERENCES users(id)',
            'FOREIGN KEY (producer_id) REFERENCES producers(id)',
            'FOREIGN KEY (storage_mode_id) REFERENCES storage_modes(id)',
        ]);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(Product::tableName());

        return true;
    }
}
