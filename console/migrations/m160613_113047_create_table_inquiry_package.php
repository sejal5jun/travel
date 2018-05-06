<?php

use yii\db\Schema;
use yii\db\Migration;

class m160613_113047_create_table_inquiry_package extends Migration
{
    public function up()
    {
        $this->createTable('{{%inquiry_package}}', [
            'id' => $this->primaryKey(),
            'inquiry_id' => $this->integer(11),
            'package_id' => $this->integer(11),
            'date_of_travel' => $this->integer(11),
            'length_of_stay' => $this->integer(11),
            'guest_count' => $this->integer(11),
            'rooms' => $this->integer(11),
            'check_in' => $this->integer(11),
            'check_out' => $this->integer(11),
            'itinerary' => $this->text(),
            'pricing' => $this->string(15),
            'terms_and_conditions' => $this->text(),
            'other_info' => $this->text(),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$this->addForeignKey("fk_inquiry_package_inquiry","inquiry_package","inquiry_id","inquiry","id","CASCADE","CASCADE");
		$this->addForeignKey("fk_inquiry_package_package","inquiry_package","package_id","package","id","CASCADE","CASCADE");
    }

    public function down()
    {
		$this->dropForeignKey("fk_inquiry_package_inquiry","inquiry_package");
		$this->dropForeignKey("fk_inquiry_package_package","inquiry_package");
        $this->dropTable('{{%inquiry_package}}');
    }
}
