<?php

use yii\db\Migration;

class m160719_080701_alter_inquiry_package_add_hotel_details extends Migration
{
    public function up()
    {
		$this->addColumn('inquiry_package', 'hotel_details', 'TEXT AFTER inquiry_details');
    }

    public function down()
    {
        $this->dropColumn('inquiry_package', 'hotel_details');
    }

}
