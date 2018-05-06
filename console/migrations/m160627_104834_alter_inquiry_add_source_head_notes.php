<?php

use yii\db\Migration;

class m160627_104834_alter_inquiry_add_source_head_notes extends Migration
{
    public function up()
    {
		 $this->addColumn('inquiry', 'head', 'INT(11) AFTER quotation_manager');
		 $this->addColumn('inquiry', 'source', 'INT(11) AFTER head');
		 $this->addColumn('inquiry', 'notes', 'TEXT AFTER source');
		 
		 $this->addForeignKey('fk_inquiry_head', 'inquiry', 'head', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
		 $this->dropForeignKey('fk_inquiry_head', 'inquiry');
		 
        $this->dropColumn('inquiry', 'notes');
        $this->dropColumn('inquiry', 'source');
        $this->dropColumn('inquiry', 'head');
    }
}
