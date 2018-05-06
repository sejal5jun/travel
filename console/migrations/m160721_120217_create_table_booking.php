<?php

use yii\db\Migration;

class m160721_120217_create_table_booking extends Migration
{
    public function up()
    {
$this->createTable('booking', [
            'id' => $this->primaryKey(),
            'inquiry_id' => $this->integer(11),
            'inquiry_package_id' => $this->integer(11),
            
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$this->addForeignKey("fk_booking_inquiry","booking","inquiry_id","inquiry","id","CASCADE","CASCADE");
		$this->addForeignKey("fk_booking_inquiry_package","booking","inquiry_package_id","inquiry_package","id","CASCADE","CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk_booking_inquiry","booking");
		$this->dropForeignKey("fk_booking_inquiry_package","booking");
        $this->dropTable('booking');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
