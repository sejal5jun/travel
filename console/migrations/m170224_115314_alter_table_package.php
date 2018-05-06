<?php

use yii\db\Migration;

class m170224_115314_alter_table_package extends Migration
{
    public function up()
    {
          $this->alterColumn('package','pricing_details','LONGTEXT');
    }

    public function down()
    {
       
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
