<?php

use yii\db\Migration;

class m160704_141506_alter_inquiry_package extends Migration
{
    public function up()
    {
		$this->renameColumn('inquiry_package','itinerary','from_date');
        $this->renameColumn('inquiry_package','pricing','return_date');
        $this->renameColumn('inquiry_package','guest_count','adult_count');
        $this->renameColumn('inquiry_package','rooms','children_count');
        $this->renameColumn('inquiry_package','check_in','room_type');
        $this->renameColumn('inquiry_package','check_out','room_count');
		
		$this->addColumn('inquiry_package', 'passenger_name', 'VARCHAR(255) AFTER package_id');
        $this->addColumn('inquiry_package', 'passenger_email', 'VARCHAR(255) AFTER passenger_name');
        $this->addColumn('inquiry_package', 'passenger_mobile', 'VARCHAR(15) AFTER passenger_email');
        $this->addColumn('inquiry_package', 'destination', 'VARCHAR(255) AFTER passenger_mobile');
        $this->addColumn('inquiry_package', 'notes', 'TEXT AFTER return_date');
		
		$this->addColumn('inquiry_package', 'package_name', 'VARCHAR(255) AFTER notes');
        $this->addColumn('inquiry_package', 'no_of_days_nights', 'VARCHAR(45) AFTER package_name');
        $this->addColumn('inquiry_package', 'category', 'INT(11) AFTER no_of_days_nights');
        $this->addColumn('inquiry_package', 'package_include', 'VARCHAR(255) AFTER category');
        $this->addColumn('inquiry_package', 'package_exclude', 'TEXT AFTER package_include');
    }

    public function down()
    {
       $this->dropColumn('inquiry_package', 'passenger_name');
       $this->dropColumn('inquiry_package', 'passenger_email');
       $this->dropColumn('inquiry_package', 'passsenger_mobile');
       $this->dropColumn('inquiry_package', 'destination');
       $this->dropColumn('inquiry_package', 'notes');
	   
	   $this->dropColumn('inquiry_package', 'package_include');
       $this->dropColumn('inquiry_package', 'package_exclude');
       $this->dropColumn('inquiry_package', 'package_name');
       $this->dropColumn('inquiry_package', 'no_of_days_nights');
       $this->dropColumn('inquiry_package', 'category');
    }

  
}
