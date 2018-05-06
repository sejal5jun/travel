<?php

use yii\db\Migration;

class m160808_104427_alter_table_inquiry extends Migration
{
   public function up()
    {
      $this->addColumn('inquiry', 'inquiry_priority', 'INT(11) AFTER mobile');
      
    }

    public function down()
    {
        $this->dropColumn('inquiry', 'inquiry_priority');
       
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
