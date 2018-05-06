<?php

use yii\db\Migration;

class m160809_115156_alter_column_inquiry_priority extends Migration
{
    public function up()
    {
          $this->alterColumn('inquiry', 'inquiry_priority', 'INT(11) DEFAULT 1');
          $this->update('inquiry', ['inquiry_priority' => 1]);
    }

    public function down()
    {
        
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
