<?php

use yii\db\Migration;

class m160628_055227_alter_inquiry_add_inquiry_id extends Migration
{
    public function up()
    {
		 $this->addColumn('inquiry', 'inquiry_id', 'INT(11) AFTER id');
    }

    public function down()
    {
        $this->dropColumn('inquiry', 'inquiry_id');
    }
}
