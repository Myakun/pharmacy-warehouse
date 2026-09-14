<?php

declare(strict_types=1);

namespace app\migrations;

use app\models\Receipt;
use yii\db\Migration;

class m220505_203541_receipts extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable(Receipt::tableName(), [
            'id' => $this->primaryKey(),
            'invoice_date' => $this->date()->notNull(),
            'invoice_number' => $this->integer()->notNull(),
            'supplier_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'FOREIGN KEY (created_by) REFERENCES users(id)',
            'FOREIGN KEY (supplier_id) REFERENCES suppliers(id)',
        ]);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(Receipt::tableName());

        return true;
    }
}
