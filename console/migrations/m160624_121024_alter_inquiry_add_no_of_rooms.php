<?php

use yii\db\Migration;

class m160624_121024_alter_inquiry_add_no_of_rooms extends Migration
{
    public function up()
    {
		 $this->addColumn('inquiry', 'room_count', 'INT(11) AFTER room_type');
    }

    public function down()
    {
        $this->dropColumn('inquiry', 'room_count');
    }
}
