<?php

declare(strict_types=1);

namespace app\migrations;

use app\models\Producer;
use yii\db\Migration;

class m220502_133021_producers extends Migration
{
    public function safeUp(): bool
    {
        $this->createTable(Producer::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(Producer::NAME_MAX_LENGTH)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'FOREIGN KEY (created_by) REFERENCES users(id)',
        ]);

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable(Producer::tableName());

        return true;
    }
}
