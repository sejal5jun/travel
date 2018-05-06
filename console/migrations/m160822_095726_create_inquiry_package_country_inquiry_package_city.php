<?php

use yii\db\Migration;

class m160822_095726_create_inquiry_package_country_inquiry_package_city extends Migration
{
    public function up()
    {	
		$this->createTable('inquiry_package_city', [
            'id' => 'pk',
            'inquiry_package_id' => 'INT(11)',
            'city_id' => 'INT(11)',
            'no_of_nights' => 'INT(11)',
            'status' => 'SMALLINT(6) NULL',
            'created_at' => 'INT NULL',
            'updated_at' => 'INT NULL', 
        ]);
		
		$this->addForeignKey('fk_inquiry_package_city_inquiry_package', 'inquiry_package_city', 'inquiry_package_id', 'package', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_inquiry_package_city_city', 'inquiry_package_city', 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');
		
		$this->createTable('inquiry_package_country', [
            'id' => 'pk',
            'inquiry_package_id' => 'INT(11)',
            'country_id' => 'INT(11)',
            'status' => 'SMALLINT(6) NULL',
            'created_at' => 'INT NULL',
            'updated_at' => 'INT NULL', 
        ]);
		
		$this->addForeignKey('fk_inquiry_package_country_inquiry_package', 'inquiry_package_country', 'inquiry_package_id', 'package', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_inquiry_package_country_country', 'inquiry_package_country', 'country_id', 'country', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
		$this->dropForeignKey('fk_inquiry_package_city_inquiry_package', 'inquiry_package_city');
		$this->dropForeignKey('fk_inquiry_package_city_city', 'inquiry_package_city');
		$this->dropTable('inquiry_package_city');
		
		$this->dropForeignKey('fk_inquiry_package_country_inquiry_package', 'inquiry_package_country');
		$this->dropForeignKey('fk_inquiry_package_country_country', 'inquiry_package_country');
		$this->dropTable('inquiry_package_country');
    }

  
}
