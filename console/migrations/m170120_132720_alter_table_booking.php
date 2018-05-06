<?php

use yii\db\Migration;

class m170120_132720_alter_table_booking extends Migration
{
    public function up()
    {
           $this->addColumn('booking','voucher_currency_id','INT(11) AFTER booking_staff');
		   $this->addColumn('booking','voucher_inr_rate','VARCHAR(255) AFTER voucher_currency_id'); 
		   $this->addColumn('booking','voucher_final_price','VARCHAR(255) AFTER voucher_inr_rate');
    }

    public function down()
    {
		 $this->dropColumn('booking','voucher_currency_id');
		 $this->dropColumn('booking','voucher_inr_rate');
		 $this->dropColumn('booking','voucher_final_price');
        
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
