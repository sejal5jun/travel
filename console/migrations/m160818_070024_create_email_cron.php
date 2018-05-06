<?php

use yii\db\Migration;

class m160818_070024_create_email_cron extends Migration
{
    public function up()
    {
		$this->dropTable('{{%followup_cron}}');
		
		$this->createTable('{{%email_cron}}', [
            'id' => $this->primaryKey(),
            'type' => $this->integer(11),
            'email' => $this->string(255),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%email_cron}}');
    }

}
