<?php

use yii\db\Migration;

class m160930_122750_alter_inquiry_package_email_cc extends Migration
{
    public function up()
    {
   $this->addColumn('inquiry_package', 'email_cc', 'TEXT AFTER passenger_email');

    }

    public function down()
    {
        $this->dropColumn('inquiry_package', 'email_cc');
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
