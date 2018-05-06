<?php

use yii\db\Migration;

class m160706_055433_alter_inquiry_add_follow_up_head_staff extends Migration
{
    public function up()
    {
		$this->dropForeignKey('fk_inquiry_head', 'inquiry');	
		$this->renameColumn('inquiry','head','inquiry_head');
		$this->addColumn('inquiry', 'follow_up_head', 'INT(11) AFTER inquiry_head');
        $this->addColumn('inquiry', 'follow_up_staff', 'INT(11) AFTER follow_up_head');
        $this->addColumn('inquiry', 'quotation_staff', 'INT(11) AFTER follow_up_staff');
        $this->addColumn('inquiry', 'no_of_days', 'INT(11) AFTER return_date');
        $this->addColumn('inquiry', 'leaving_from', 'VARCHAR(255) AFTER destination');
        $this->addColumn('inquiry', 'inquiry_details', 'TEXT AFTER notes');
		
		$this->addForeignKey('fk_inquiry_follow_up_head', 'inquiry', 'follow_up_head', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_inquiry_follow_up_staff', 'inquiry', 'follow_up_staff', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_inquiry_quotation_staff', 'inquiry', 'quotation_staff', 'user', 'id', 'CASCADE', 'CASCADE');
		
		 $this->addForeignKey('fk_inquiry_head', 'inquiry', 'inquiry_head', 'user', 'id', 'CASCADE', 'CASCADE');
	}

    public function down()
    {
	   $this->dropForeignKey('fk_inquiry_follow_up_head', 'inquiry');	
	   $this->dropForeignKey('fk_inquiry_follow_up_staff', 'inquiry');	
	   $this->dropForeignKey('fk_inquiry_quotation_staff', 'inquiry');	
	   
       $this->dropColumn('inquiry', 'follow_up_head');
       $this->dropColumn('inquiry', 'follow_up_staff');
       $this->dropColumn('inquiry', 'quotation_staff');
       $this->dropColumn('inquiry', 'leaving_from');
       $this->dropColumn('inquiry', 'inquiry_details');
       $this->dropColumn('inquiry', 'no_of_days');
      
    }

}
