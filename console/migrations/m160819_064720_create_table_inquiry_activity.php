<?php

use yii\db\Migration;

class m160819_064720_create_table_inquiry_activity extends Migration
{
   public function up()
    {
		$this->createTable('{{%inquiry_activity}}', [
            'id' => $this->primaryKey(),
            'inquiry_id' => $this->integer(11),
            'user_id' => $this->integer(11),
            'date' => $this->integer(11),
            'type' => $this->integer(11),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$this->addForeignKey("fk_inquiry_activity_inquiry","inquiry_activity","inquiry_id","inquiry","id","CASCADE","CASCADE");
		$this->addForeignKey("fk_inquiry_activity_user","inquiry_activity","user_id","user","id","CASCADE","CASCADE");
		$this->dropTable('{{%package_banner}}');
		
    }

    public function down()
    {
		$this->dropForeignKey("fk_inquiry_activity_inquiry","inquiry_activity");
		$this->dropForeignKey("fk_inquiry_activity_user","inquiry_activity");
        $this->dropTable('{{%inquiry_activity}}');
    }
}
