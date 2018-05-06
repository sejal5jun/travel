<?php

use yii\db\Migration;

class m160801_142445_create_country extends Migration
{
     public function up()
    {
		$this->createTable('country', [
            'id' => 'pk',
            'name' => 'VARCHAR(255)',
            'status' => 'INT NULL',
            'created_at' => 'INT NULL',
            'updated_at' => 'INT NULL', 
        ]);
    }

    public function down()
    {
		$this->dropTable('country');
    }
}
