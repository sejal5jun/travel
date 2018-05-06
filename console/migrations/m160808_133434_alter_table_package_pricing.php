<?php

use yii\db\Migration;

class m160808_133434_alter_table_package_pricing extends Migration
{
    public function up()
    {
       $this->addForeignKey('fk_package_pricing_price_type', 'package_pricing', 'type', 'price_type', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_package_pricing_price_type', 'package_pricing');
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
