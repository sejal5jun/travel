<?php

use yii\db\Migration;

class m160708_115248_alter_inquiry_package_add_inquiry_details extends Migration
{
    public function up()
    {
		$this->addColumn('inquiry_package', 'inquiry_details', 'TEXT AFTER notes');
		$this->alterColumn('inquiry_package', 'package_include', 'TEXT');
    }

    public function down()
    {
        $this->dropColumn('inquiry_package', 'inquiry_details');
    }
}
