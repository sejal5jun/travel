<?php

use yii\db\Migration;

class m160819_103909_create_record_login extends Migration
{
    public function up()
    {
		$this->createTable('{{%record_login}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11),
            'login_time' => $this->integer(11),
            'logout_time' => $this->integer(11),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$this->addForeignKey("fk_record_login_user","record_login","user_id","user","id","CASCADE","CASCADE");
    }

    public function down()
    {
		$this->dropForeignKey("fk_record_login_user","record_login");
        $this->dropTable('{{%record_login}}');
    }
}
