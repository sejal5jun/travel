<?php

use yii\db\Migration;

class m160817_050242_create_schedule_followup extends Migration
{
    public function up()
    {
		$this->createTable('schedule_followup', [
            'id' => $this->primaryKey(),
            'inquiry_id' => $this->integer(11),
            'passenger_email' => $this->string(255),
			'text_body' => $this->text(),
			'scheduled_at' => $this->integer(11),
			'scheduled_by' => $this->integer(11),
            'is_sent' => $this->smallInteger(6),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$this->addForeignKey("fk_schedule_followup_inquiry","schedule_followup","inquiry_id","inquiry","id","CASCADE","CASCADE");
		$this->addForeignKey("fk_schedule_followup_user","schedule_followup","scheduled_by","user","id","CASCADE","CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk_schedule_followup_inquiry","schedule_followup");
        $this->dropForeignKey("fk_schedule_followup_user","schedule_followup");
        $this->dropTable('schedule_followup');
    }
}
