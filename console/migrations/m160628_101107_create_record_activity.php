<?php

use yii\db\Migration;

/**
 * Handles the creation for table `record_activity`.
 */
class m160628_101107_create_record_activity extends Migration
{
    public function up()
    {
        $this->createTable('{{%record_activity}}', [
            'id' => $this->primaryKey(),
            'inquiry_id' => $this->integer(11),
            'activity' =>$this->integer(11),
            'status' => $this->smallInteger(6),
            'created_by' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);

        $this->addForeignKey("fk_record_activity_inquiry","record_activity","inquiry_id","inquiry","id","CASCADE","CASCADE");
        $this->addForeignKey("fk_record_activity_user","record_activity","created_by","user","id","CASCADE","CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk_record_activity_inquiry","record_activity");
        $this->dropForeignKey("fk_record_activity_user","record_activity");
        $this->dropTable('{{%record_activity}}');
    }
}
