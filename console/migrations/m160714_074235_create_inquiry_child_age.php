<?php

use yii\db\Migration;

class m160714_074235_create_inquiry_child_age extends Migration
{
    public function up()
    {
		$this->createTable('inquiry_child_age', [
            'id' => $this->primaryKey(),
            'inquiry_id' => $this->integer(11),
            'age' => $this->string(45),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$this->addForeignKey("fk_inquiry_child_age_inquiry","inquiry_child_age","inquiry_id","inquiry","id","CASCADE","CASCADE");
		
		$this->createTable('inquiry_package_child_age', [
            'id' => $this->primaryKey(),
            'inquiry_package_id' => $this->integer(11),
            'age' => $this->string(45),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$this->addForeignKey("fk_inquiry_package_child_age_inquiry_package","inquiry_package_child_age","inquiry_package_id","inquiry_package","id","CASCADE","CASCADE");
    }

    public function down()
    {
		$this->dropForeignKey("fk_inquiry_child_age_inquiry","inquiry_child_age");
        $this->dropTable("inquiry_child_age");
		
		$this->dropForeignKey("fk_inquiry_package_child_age_inquiry_package","inquiry_package_child_age");
        $this->dropTable("inquiry_package_child_age");
    }
}
