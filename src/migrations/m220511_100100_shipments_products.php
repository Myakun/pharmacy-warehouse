<?php

declare(strict_types=1);

use app\models\ShipmentProduct;
use yii\db\Migration;

class m220511_100100_shipments_products extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable(ShipmentProduct::tableName(), [
            'id' => $this->primaryKey(),
            'packages_amount' => $this->integer()->notNull(),
            'product_storage_cell_id' => $this->integer()->notNull(),
            'shipment_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'FOREIGN KEY (created_by) REFERENCES users(id)',
            'FOREIGN KEY (product_storage_cell_id) REFERENCES products_storage_cells(id)',
            'FOREIGN KEY (shipment_id) REFERENCES shipments(id)',
        ]);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(ShipmentProduct::tableName());

        return true;
    }
}
