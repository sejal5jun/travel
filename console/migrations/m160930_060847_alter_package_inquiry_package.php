<?php

use yii\db\Migration;

class m160930_060847_alter_package_inquiry_package extends Migration
{
    public function up()
    {
	$this->addColumn('package', 'till_validity', 'TEXT AFTER validity');
	$this->addColumn('inquiry_package', 'till_validity', 'TEXT AFTER validity');
    }

    public function down()
    {
      $this->dropColumn('package', 'till_validity');
      $this->dropColumn('inquiry_package', 'till_validity');
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
