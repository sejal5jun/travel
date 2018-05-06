<?php

use yii\db\Migration;

class m160912_090346_alter_booking_add_inr_rate extends Migration
{
    public function up()
    {
		$this->addColumn('booking', 'inr_rate', 'VARCHAR(45) AFTER currency_id');
    }

    public function down()
    {
         $this->dropColumn('booking', 'inr_rate');
    }
}
