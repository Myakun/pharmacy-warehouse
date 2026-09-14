<?php

declare(strict_types=1);

use app\models\Customer;
use yii\db\Migration;

class m220503_220115_customers extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable(Customer::tableName(), [
            'id' => $this->primaryKey(),
            'address' => $this->string(Customer::ADDRESS_MAX_LENGTH)->notNull(),
            'contact_person' => $this->string(Customer::CONTACT_PERSON_MAX_LENGTH)->notNull(),
            'contract_date' => $this->date()->notNull(),
            'contract_number' => $this->integer()->notNull(),
            'name' => $this->string(Customer::NAME_MAX_LENGTH)->notNull(),
            'phone' => $this->string(Customer::PHONE_LENGTH)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'FOREIGN KEY (created_by) REFERENCES users(id)',
        ]);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(Customer::tableName());

        return true;
    }
}
