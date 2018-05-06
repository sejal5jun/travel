<?php

use yii\db\Migration;

class m160901_074749_alter_inquiry_package_add_quotation_details extends Migration
{
     public function up()
    {
		$this->addColumn('inquiry_package', 'quotation_details', 'LONGTEXT AFTER hotel_details');
		$this->addColumn('inquiry_package', 'is_quotation_sent', 'SMALLINT(6) AFTER quotation_details');
    }

    public function down()
    {
        $this->dropColumn('inquiry_package', 'quotation_details');
        $this->dropColumn('inquiry_package', 'is_quotation_sent');
    }
}
