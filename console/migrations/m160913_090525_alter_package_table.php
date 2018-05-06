<?php

use yii\db\Migration;

class m160913_090525_alter_package_table extends Migration
{
    public function up()
    {
 $this->addColumn('package', 'pricing_details', 'TEXT AFTER other_info');
 $this->addColumn('inquiry_package', 'pricing_details', 'TEXT AFTER other_info');
    }

    public function down()
    {
       $this->dropColumn('package', 'pricing_details');
        $this->dropColumn('inquiry_package', 'pricing_details');
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
