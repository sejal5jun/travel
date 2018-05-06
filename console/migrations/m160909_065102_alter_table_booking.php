<?php

use yii\db\Migration;

class m160909_065102_alter_table_booking extends Migration
{
    public function up()
    {
    $this->addColumn('booking', 'booking_staff', 'INT(11) AFTER final_price');
	$this->addForeignKey('fk_booking_user', 'booking', 'booking_staff', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
		$this->dropForeignKey('fk_booking_user', 'booking');
        $this->dropColumn('booking', 'booking_staff');
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
