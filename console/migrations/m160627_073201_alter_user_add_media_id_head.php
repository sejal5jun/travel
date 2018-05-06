<?php

use yii\db\Migration;

class m160627_073201_alter_user_add_media_id_head extends Migration
{
    public function up()
    {
		 $this->addColumn('user', 'is_head', 'SMALLINT(6) DEFAULT 0 AFTER role');
		 $this->addColumn('user', 'media_id', 'INT(11) AFTER email');
		 
		 $this->addForeignKey('fk_user_media', 'user', 'media_id', 'media', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_user_media', 'user');
        $this->dropColumn('user', 'is_head');
        $this->dropColumn('user', 'media_id');
    }
}
