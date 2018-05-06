<?php

use yii\db\Migration;

class m160819_070544_create_table_activity_count extends Migration
{
    public function up()
    {
		$this->createTable('{{%activity_count}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11),
            'date' => $this->integer(11),
			'quotation_count' => $this->integer(11),
            'followup_count' => $this->integer(11),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$this->addForeignKey("fk_activity_count_user","activity_count","user_id","user","id","CASCADE","CASCADE");
    }

    public function down()
    {
		$this->dropForeignKey("fk_activity_count_user","activity_count");
        $this->dropTable('{{%activity_count}}');
    }
}
