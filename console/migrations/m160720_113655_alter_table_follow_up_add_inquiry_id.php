<?php

use yii\db\Migration;

class m160720_113655_alter_table_follow_up_add_inquiry_id extends Migration
{
    public function up()
    {
      $this->addColumn('followup', 'inquiry_id', 'INT(11) AFTER id');
    }

    public function down()
    {
         $this->dropColumn('followup', 'inquiry_id');
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
