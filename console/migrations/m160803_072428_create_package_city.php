<?php

use yii\db\Migration;

class m160803_072428_create_package_city extends Migration
{
    public function up()
    {
		$this->createTable('package_city', [
            'id' => 'pk',
            'package_id' => 'INT(11)',
            'city_id' => 'INT(11)',
            'no_of_nights' => 'INT(11)',
            'status' => 'SMALLINT(6) NULL',
            'created_at' => 'INT NULL',
            'updated_at' => 'INT NULL', 
        ]);
		
		$this->addForeignKey('fk_package_city_package', 'package_city', 'package_id', 'package', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_package_city_city', 'package_city', 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
		$this->dropForeignKey('fk_package_city_package', 'package_city');
		$this->dropForeignKey('fk_package_city_city', 'package_city');
		$this->dropTable('package_city');
    }
}
