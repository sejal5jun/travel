<?php

use yii\db\Migration;

/**
 * Handles the creation for table `room_type`.
 */
class m160713_085342_create_room_type extends Migration
{
   public function up()
    {
		$this->createTable('room_type', [
            'id' => 'pk',
            'type' => 'VARCHAR(255)',
            'status' => 'INT NULL',
            'created_at' => 'INT NULL',
            'updated_at' => 'INT NULL', 
        ]);		
    }

    public function down()
    {
		$this->dropTable('room_type');
    }
}
