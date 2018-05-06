<?php

use yii\db\Migration;

class m160713_110658_alter_inquiry_add_customer_type_agent_id extends Migration
{
    public function up()
    {
		 $this->addColumn('inquiry', 'customer_type', 'INT(11) AFTER type');
		 $this->addColumn('inquiry', 'agent_id', 'INT(11) AFTER customer_type');
		 
		 $this->addForeignKey('fk_inquiry_agent', 'inquiry', 'agent_id', 'agent', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
		$this->dropForeignKey('fk_inquiry_agent', 'inquiry');
		 
        $this->dropColumn('inquiry', 'customer_type');
        $this->dropColumn('inquiry', 'agent_id');
    }

}
