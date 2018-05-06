<?php

use yii\db\Schema;
use yii\db\Migration;

class m160613_110421_create_table_inquiry extends Migration
{
    public function up()
    {
        $this->createTable('{{%inquiry}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'email' => $this->string(255),
            'mobile' => $this->string(15),
            'type' => $this->smallInteger(6),
            'destination' => $this->string(255),
            'adult_count' => $this->integer(11),
            'children_count' => $this->integer(11),
            'room_type' => $this->smallInteger(6),
            'from_date' => $this->integer(11),
            'return_date' => $this->integer(11),
            'quotation_manager' => $this->integer(11),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$this->addForeignKey("fk_inquiry_user","inquiry","quotation_manager","user","id","CASCADE","CASCADE");
    }

    public function down()
    {
		$this->dropForeignKey("fk_inquiry_user","inquiry");
        $this->dropTable('{{%inquiry}}');
    }
}
