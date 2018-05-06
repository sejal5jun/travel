<?php

use yii\db\Migration;

class m160727_130553_alter_record_activity_add_notes extends Migration
{
   public function up()
    {
		$this->addColumn('record_activity', 'notes', 'TEXT AFTER activity');
    }

    public function down()
    {
        $this->dropColumn('record_activity', 'notes');
    }
}
