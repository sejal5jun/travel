<?php

use yii\db\Schema;
use yii\db\Migration;

class m160613_114702_create_table_followup extends Migration
{
    public function up()
    {
        $this->createTable('{{%followup}}', [
            'id' => $this->primaryKey(),
            'inquiry_package_id' => $this->integer(11),
            'date' => $this->integer(11),
            'note' => $this->text(),
            'by' => $this->integer(11),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$this->addForeignKey("fk_followup_inquiry_package","followup","inquiry_package_id","inquiry_package","id","CASCADE","CASCADE");
		$this->addForeignKey("fk_followup_user","followup","by","user","id","CASCADE","CASCADE");
    }

    public function down()
    {
		$this->dropForeignKey("fk_followup_inquiry_package","followup");
		$this->dropForeignKey("fk_followup_user","followup");
        $this->dropTable('{{%followup}}');
    }
}
