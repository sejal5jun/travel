<?php

use yii\db\Migration;

class m160715_064650_alter_package_add_type extends Migration
{
    public function up()
    {
		$this->addColumn('package', 'type', 'INT(11) AFTER id');
		
		$this->dropColumn('inquiry', 'room_type');
		$this->dropColumn('inquiry_package', 'room_type');
    }

    public function down()
    {
       $this->dropColumn('package', 'type');
    }
}
