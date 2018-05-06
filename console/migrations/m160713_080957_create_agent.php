<?php

use yii\db\Migration;

/**
 * Handles the creation for table `agent`.
 */
class m160713_080957_create_agent extends Migration
{
    public function up()
    {
		$this->createTable('agent', [
            'id' => 'pk',
            'name' => 'VARCHAR(255)',
            'city_id' => 'INT(11) NOT NULL',
            'status' => 'INT NULL',
            'created_at' => 'INT NULL',
            'updated_at' => 'INT NULL', 
        ]);
		
		$this->addForeignKey("fk_agent_city","agent","city_id","city","id","CASCADE","CASCADE");
    }

    public function down()
    {
		$this->dropForeignKey("fk_agent_city","agent");
		$this->dropTable('agent');
    }
}
