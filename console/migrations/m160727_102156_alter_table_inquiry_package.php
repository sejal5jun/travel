<?php

use yii\db\Migration;

class m160727_102156_alter_table_inquiry_package extends Migration
{
    public function up()
    {
      $this->addColumn('inquiry_package', 'is_itinerary', 'INT(11) AFTER package_id');
    }

    public function down()
    {
        $this->dropColumn('inquiry_package', 'is_itinerary');
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
