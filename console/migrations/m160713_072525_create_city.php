<?php

use yii\db\Migration;

/**
 * Handles the creation for table `city`.
 */
class m160713_072525_create_city extends Migration
{
    public function up()
    {
		$this->createTable('city', [
            'id' => 'pk',
            'name' => 'VARCHAR(255)',
            'status' => 'INT NULL',
            'created_at' => 'INT NULL',
            'updated_at' => 'INT NULL', 
        ]);
    }

    public function down()
    {
		$this->dropTable('city');
    }
}
