<?php

use yii\db\Migration;

/**
 * Handles the creation for table `currency`.
 */
class m160629_105246_create_currency extends Migration
{
        public function up()
    {
        $this->createTable('{{%currency}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(45),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
		
		$time = time();
		
		$this->insert('currency',array(
         'name'=>'INR',
         'status'=>10,
		 'created_at' => $time,
         'updated_at' => $time
		));
		
		$this->insert('currency',array(
         'name'=>'AUD',
		 'status'=>10,
		 'created_at' => $time,
         'updated_at' => $time
		));
		
		$this->insert('currency',array(
         'name'=>'EURO',
		 'status'=>10,
		 'created_at' => $time,
         'updated_at' => $time
		));
		
		$this->insert('currency',array(
         'name'=>'CAD',
		 'status'=>10,
		 'created_at' => $time,
         'updated_at' => $time
		));
		
		$this->insert('currency',array(
         'name'=>'USD',
		 'status'=>10,
		 'created_at' => $time,
         'updated_at' => $time
		));
		
		$this->insert('currency',array(
         'name'=>'NZD',
		 'status'=>10,
		 'created_at' => $time,
         'updated_at' => $time
		));

    }

    public function down()
    {
        $this->dropTable('{{%currency}}');
    }
}
