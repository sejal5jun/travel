<?php

use yii\db\Migration;

class m160718_111722_alter_media_add_media_type extends Migration
{
    public function up()
    {
		$this->addColumn('media', 'media_type', 'INT(11) AFTER id');
    }

    public function down()
    {
       $this->dropColumn('media', 'media_type');
    }

}
