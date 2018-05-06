<?php

use yii\db\Migration;

class m161027_120448_create_table_ip_restrictions extends Migration
{
    public function up()
    {
        $this->createTable('{{%ip_restrictions%}}', [
            'id' => $this->primaryKey(),
            'ip' => $this->string(255),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%ip_restrictions%}}');
    }
}
