<?php

use yii\db\Migration;

class m160722_120941_alter_table_inquiry_package extends Migration
{
    public function up()
    {
		$this->addColumn('inquiry_package', 'leaving_from', 'VARCHAR(255) AFTER destination');
    }

    public function down()
    {
        $this->addColumn('inquiry_package', 'leaving_from');
    }

}
