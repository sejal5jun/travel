<?php

use yii\db\Migration;

class m160824_101709_create_table_record_inquiry extends Migration
{
    public function up()
    {
		$this->createTable('{{%record_inquiry}}', [
            'id' => $this->primaryKey(),
            'new_inquiry_count' => $this->integer(11),
            'quotation_count' => $this->integer(11),
            'followup_count' => $this->integer(11),
            'booking_count' => $this->integer(11),
            'cancellation_count' => $this->integer(11),
            'date' => $this->integer(11),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
    }

    public function down()
    {
        $this->dropTable('{{%record_inquiry}}');
    }
}
