<?php

declare(strict_types=1);

use app\models\ProductStorageCell;
use yii\db\Migration;

class m220510_121001_products_storage_cells extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable(ProductStorageCell::tableName(), [
            'id' => $this->primaryKey(),
            'amount' => $this->integer()->notNull(),
            'in' => $this->integer()->notNull(),
            'receipt_product_id' => $this->integer()->notNull(),
            'storage_cell_id' => $this->integer()->notNull(),
            'out' => $this->integer()->notNull(),
            'FOREIGN KEY (receipt_product_id) REFERENCES receipts(id)',
            'FOREIGN KEY (storage_cell_id) REFERENCES storage_cells(id)',
        ]);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(ProductStorageCell::tableName());

        return true;
    }
}
