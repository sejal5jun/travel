<?php

use yii\db\Migration;

class m160801_081759_alter_user_add_head_drop_is_head extends Migration
{
    public function up()
    {
		$this->addColumn('user', 'head', 'INT(11) AFTER role');
		$this->dropColumn('user', 'is_head');
		
		$this->addForeignKey('fk_staff_head', 'user', 'head', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
		$this->dropForeignKey('fk_staff_head', 'user');	
        $this->dropColumn('user', 'head');
    }
}
