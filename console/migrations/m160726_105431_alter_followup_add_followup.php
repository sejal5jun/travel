<?php

use yii\db\Migration;

class m160726_105431_alter_followup_add_followup extends Migration
{
    public function up()
    {
		$this->addColumn('followup', 'is_followup', 'SMALLINT(6) AFTER status');
    }

    public function down()
    {
        $this->addColumn('followup', 'is_followup');
    }

}
