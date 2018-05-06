<?php

use yii\db\Migration;

class m160829_112024_alter_user_add_mobile extends Migration
{
    public function up()
    {
		$this->addColumn('user', 'mobile', 'VARCHAR(15) AFTER email');
    }

    public function down()
    {
        $this->dropColumn('user', 'mobile');
    }
}
