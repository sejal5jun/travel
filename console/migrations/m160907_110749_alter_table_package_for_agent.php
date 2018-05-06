<?php

use yii\db\Migration;

class m160907_110749_alter_table_package_for_agent extends Migration
{
    public function up()
    {
         $this->addColumn('package', 'for_agent', 'INT(11) AFTER validity');
    }

    public function down()
    {
        $this->dropColumn('package', 'for_agent');
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
