<?php

use yii\db\Migration;

class m160907_045931_alter_tabel_package extends Migration
{
    public function up()
    {
		$this->addColumn('package', 'validity', 'TEXT AFTER no_of_days_nights');
		$this->addColumn('inquiry_package', 'validity', 'TEXT AFTER no_of_days_nights');

    }

    public function down()
    {
          $this->dropColumn('package', 'validity');
          $this->dropColumn('inquiry_package', 'validity');
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
