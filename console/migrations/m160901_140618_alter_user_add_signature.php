<?php

use yii\db\Migration;

class m160901_140618_alter_user_add_signature extends Migration
{
    public function up()
    {
		$this->addColumn('user', 'signature', 'TEXT AFTER mobile');

    }

    public function down()
    {
        $this->dropColumn('user', 'signature');
    }
}
