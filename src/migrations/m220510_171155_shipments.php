<?php

declare(strict_types=1);

use app\models\Shipment;
use yii\db\Migration;

class m220510_171155_shipments extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable(Shipment::tableName(), [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'invoice_date' => $this->date()->notNull(),
            'invoice_number' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'FOREIGN KEY (created_by) REFERENCES users(id)',
            'FOREIGN KEY (customer_id) REFERENCES customers(id)',
        ]);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(Shipment::tableName());

        return true;
    }
}
