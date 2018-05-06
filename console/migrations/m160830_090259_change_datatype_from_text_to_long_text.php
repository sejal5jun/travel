<?php

use yii\db\Migration;

class m160830_090259_change_datatype_from_text_to_long_text extends Migration
{
    public function up()
    {
		$this->alterColumn('inquiry', 'inquiry_details', 'LONGTEXT');
		$this->alterColumn('inquiry_package', 'inquiry_details', 'LONGTEXT');
		$this->alterColumn('inquiry_package', 'hotel_details', 'LONGTEXT');
    }

    public function down()
    {
       
    }

   
}
