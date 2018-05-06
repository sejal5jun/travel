<?php

use yii\db\Migration;

class m160623_101407_alter_user_add_role extends Migration
{
    public function up()
    {
		 $this->addColumn('user', 'role', 'INT(11) AFTER password_reset_token');
    }

    public function down()
    {
        $this->dropColumn('user', 'role');
    }
}
