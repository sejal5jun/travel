<?php

use yii\db\Migration;

class m160722_103441_alter_table_booking extends Migration
{
    public function up()
    {
      $this->addColumn('booking', 'booking_id', 'VARCHAR(255) AFTER id');
    }

    public function down()
    {
            $this->addColumn('booking', 'booking_id');
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
