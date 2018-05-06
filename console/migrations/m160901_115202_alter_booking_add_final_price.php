<?php

use yii\db\Migration;

class m160901_115202_alter_booking_add_final_price extends Migration
{
    public function up()
    {
		$this->addColumn('booking', 'currency_id', 'INT(11) AFTER inquiry_package_id');
		$this->addColumn('booking', 'final_price', 'VARCHAR(45) AFTER currency_id');
		
		$this->addForeignKey('fk_booking_currency', 'booking', 'currency_id', 'currency', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
		$this->dropForeignKey('fk_booking_currency', 'booking');
        $this->dropColumn('booking', 'currency_id');
        $this->dropColumn('booking', 'final_price');
    }
}
