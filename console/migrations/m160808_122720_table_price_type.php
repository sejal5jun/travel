<?php

use yii\db\Migration;

class m160808_122720_table_price_type extends Migration
{
    public function up()
    {
		$this->createTable('price_type', [
            'id' => 'pk',
            'type' => 'VARCHAR(255)',
            'status' => 'INT NULL',
            'created_at' => 'INT NULL',
            'updated_at' => 'INT NULL', 
        ]);		
    }

    public function down()
    {
		$this->dropTable('price_type');
    }
}
