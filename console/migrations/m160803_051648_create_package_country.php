<?php

use yii\db\Migration;

class m160803_051648_create_package_country extends Migration
{
    public function up()
    {
		$this->createTable('package_country', [
            'id' => 'pk',
            'package_id' => 'INT(11)',
            'country_id' => 'INT(11)',
            'status' => 'SMALLINT(6) NULL',
            'created_at' => 'INT NULL',
            'updated_at' => 'INT NULL', 
        ]);
		
		$this->addForeignKey('fk_package_country_package', 'package_country', 'package_id', 'package', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_package_country_country', 'package_country', 'country_id', 'country', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
		$this->dropForeignKey('fk_package_country_media', 'package_country');
		$this->dropForeignKey('fk_package_country_media', 'package_country');
		$this->dropTable('package_country');
    }
}
