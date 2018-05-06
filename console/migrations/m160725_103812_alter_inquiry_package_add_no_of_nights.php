<?php

use yii\db\Migration;

class m160725_103812_alter_inquiry_package_add_no_of_nights extends Migration
{
    public function up()
    {
		$this->addColumn('inquiry_package', 'no_of_nights', 'INT(11) AFTER return_date');
    }

    public function down()
    {
        $this->addColumn('inquiry_package', 'no_of_nights');
    }
}
