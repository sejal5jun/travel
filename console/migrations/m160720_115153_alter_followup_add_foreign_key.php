<?php

use yii\db\Migration;

class m160720_115153_alter_followup_add_foreign_key extends Migration
{
    public function up()
    {
		$this->addForeignKey("fk_followup_inquiry","followup","inquiry_id","inquiry","id","CASCADE","CASCADE");
    }

    public function down()
    {
       $this->dropForeignKey("fk_followup_inquiry","followup");
    }

   
}
