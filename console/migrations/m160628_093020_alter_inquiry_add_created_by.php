<?php

use yii\db\Migration;

class m160628_093020_alter_inquiry_add_created_by extends Migration
{
    public function up()
    {
        $this->addColumn('inquiry', 'created_by', 'INT(11) AFTER status');
        $this->addForeignKey('fk_inquiry_created', 'inquiry', 'created_by', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_inquiry_created', 'inquiry');
        $this->dropColumn('inquiry', 'created_by');
    }
}
