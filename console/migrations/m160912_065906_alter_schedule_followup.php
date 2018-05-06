<?php

use yii\db\Migration;

class m160912_065906_alter_schedule_followup extends Migration
{
    public function up()
    {
     $this->addColumn('schedule_followup', 'inquiry_package_id', 'INT(11) AFTER inquiry_id');
	$this->addForeignKey('fk_schedule_followup_inquiry_package', 'schedule_followup', 'inquiry_package_id', 'inquiry_package', 'id', 'SET NULL', 'SET NULL');
    }

    public function down()
    {
       $this->dropForeignKey('fk_schedule_followup_inquiry_package', 'schedule_followup');
        $this->dropColumn('schedule_followup', 'inquiry_package_id');
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
