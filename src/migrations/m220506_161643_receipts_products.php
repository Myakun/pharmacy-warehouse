<?php

declare(strict_types=1);

use app\models\ReceiptProduct;
use yii\db\Migration;

class m220506_161643_receipts_products extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable(ReceiptProduct::tableName(), [
            'id' => $this->primaryKey(),
            'expiration_date' => $this->date()->notNull(),
            'packages_amount' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'production_date' => $this->date()->notNull(),
            'receipt_id' => $this->integer()->notNull(),
            'series' => $this->string(ReceiptProduct::SERIES_MAX_LENGTH)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'FOREIGN KEY (created_by) REFERENCES users(id)',
            'FOREIGN KEY (product_id) REFERENCES products(id)',
            'FOREIGN KEY (receipt_id) REFERENCES receipts(id)',
        ]);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(ReceiptProduct::tableName());

        return true;
    }
}
