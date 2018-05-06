<?php

use yii\db\Migration;

class m160820_105038_alter_quotation_pricing_add_type_index extends Migration
{
    public function up()
    {
		$this->addForeignKey("fk_quotation_pricing_price_type","quotation_pricing","type","price_type","id","CASCADE","CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk_quotation_pricing_price_type","quotation_pricing");
    }
}
