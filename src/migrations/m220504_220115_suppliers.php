<?php

declare(strict_types=1);

namespace app\migrations;

use app\models\Supplier;
use yii\db\Migration;

class m220504_220115_suppliers extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable(Supplier::tableName(), [
            'id' => $this->primaryKey(),
            'address' => $this->string(Supplier::ADDRESS_MAX_LENGTH)->notNull(),
            'contact_person' => $this->string(Supplier::CONTACT_PERSON_MAX_LENGTH)->notNull(),
            'contract_date' => $this->date()->notNull(),
            'contract_number' => $this->integer()->notNull(),
            'name' => $this->string(Supplier::NAME_MAX_LENGTH)->notNull(),
            'phone' => $this->string(Supplier::PHONE_LENGTH)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'FOREIGN KEY (created_by) REFERENCES users(id)',
        ]);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(Supplier::tableName());

        return true;
    }
}
