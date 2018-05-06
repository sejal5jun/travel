<?php

use yii\db\Migration;

class m160721_124127_create_followup_cron extends Migration
{
    public function up()
    {
		$this->createTable('followup_cron', [
            'id' => $this->primaryKey(),
            'followup_id' => $this->integer(11),
            'sent' => $this->smallInteger(6)
        ]);
        $this->addForeignKey("fk_followup_cron_followup","followup_cron","followup_id","followup","id","CASCADE","CASCADE");
    }

    public function down()
    {
       $this->dropForeignKey("fk_followup_cron_followup","followup_cron");
       $this->dropTable("followup_cron");
    }

}
