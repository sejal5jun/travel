<?php

use yii\db\Migration;

/**
 * Handles the creation for table `media`.
 */
class m160627_073050_create_media extends Migration
{
     public function up()
    {
		$this->createTable('media', [
            'id' => 'pk',
            'alt' => 'VARCHAR(255)',
            'file_name' => 'VARCHAR(255)',
            'file_type' => 'VARCHAR(45)',
            'file_size' => 'INT NULL',
            'created_at' => 'INT NULL',
            'updated_at' => 'INT NULL', 
        ]);
    }

    public function down()
    {
		$this->dropTable('media');
    }
}
