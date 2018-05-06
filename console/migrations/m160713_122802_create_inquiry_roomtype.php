<?php

use yii\db\Migration;

class m160713_122802_create_inquiry_roomtype extends Migration
{
    public function up()
    {
		$this->createTable('inquiry_room_type', [
            'id' => $this->primaryKey(),
            'inquiry_id' => $this->integer(11),
            'room_type_id' => $this->integer(11),
             'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
        $this->addForeignKey("fk_inquiry_room_type_inquiry","inquiry_room_type","inquiry_id","inquiry","id","CASCADE","CASCADE");
        $this->addForeignKey("fk_inquiry_room_type_room_type","inquiry_room_type","room_type_id","room_type","id","CASCADE","CASCADE");
		
		$this->createTable('inquiry_package_room_type', [
            'id' => $this->primaryKey(),
            'inquiry_package_id' => $this->integer(11),
            'room_type_id' => $this->integer(11),
             'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		$this->addForeignKey("fk_inquiry_package_room_type_package","inquiry_package_room_type","inquiry_package_id","inquiry_package","id","CASCADE","CASCADE");
        $this->addForeignKey("fk_inquiry_package_room_type_room_type","inquiry_package_room_type","room_type_id","room_type","id","CASCADE","CASCADE");
		
		
        //$this->addForeignKey("fk_record_activity_user","record_activity","created_by","user","id","CASCADE","CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk_inquiry_room_type_inquiry","inquiry_room_type");
        $this->dropForeignKey("fk_inquiry_room_type_room_type","inquiry_room_type");
        $this->dropTable("inquiry_room_type");
		
		 $this->dropForeignKey("fk_inquiry_package_room_type_package","inquiry_package_room_type");
        $this->dropForeignKey("fk_inquiry_package_room_type_room_type","inquiry_package_room_type");
        $this->dropTable("inquiry_package_room_type");
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
