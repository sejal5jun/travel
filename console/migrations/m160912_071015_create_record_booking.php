<?php

use yii\db\Migration;

class m160912_071015_create_record_booking extends Migration
{
     public function up()
    {
		$this->createTable('{{%record_booking}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11),
			'amount' => $this->string(45),	
            'month_year' => $this->integer(11),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$this->addForeignKey('fk_record_booking_user', 'record_booking', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
		$this->dropForeignKey('fk_record_booking_user', 'record_booking');
        $this->dropTable('{{%record_booking}}');
    }
}
