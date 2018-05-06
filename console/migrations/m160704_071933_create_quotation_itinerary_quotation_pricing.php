<?php

use yii\db\Migration;

/**
 * Handles the creation for table `quotation_itinerary_quotation_pricing`.
 */
class m160704_071933_create_quotation_itinerary_quotation_pricing extends Migration
{
	public function up()
    {
		$this->createTable('{{%quotation_pricing}}', [
            'id' => $this->primaryKey(),
            'quotation_id' => $this->integer(11),
            'currency_id' => $this->integer(11),
            'type' =>$this->integer(11),
            'price' =>$this->string(45),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);

        $this->addForeignKey("fk_quotation_pricing_inquiry_package","quotation_pricing","quotation_id","inquiry_package","id","CASCADE","CASCADE");
        $this->addForeignKey("fk_quotation_pricing_currency","quotation_pricing","currency_id","currency","id","CASCADE","CASCADE");
		
		$this->createTable('{{%quotation_itinerary}}', [
            'id' => $this->primaryKey(),
            'quotation_id' => $this->integer(11),
            'name' => $this->text(),
            'no_of_itineraries' =>$this->integer(11),
            'title' =>$this->string(255),
            'description' =>$this->text(),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);

        $this->addForeignKey("fk_quotation_itinerary_inquiry_package","quotation_itinerary","quotation_id","inquiry_package","id","CASCADE","CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk_quotation_pricing_inquiry_package","quotation_pricing");
        $this->dropForeignKey("fk_quotation_pricing_currency","quotation_pricing");
        $this->dropTable('{{%quotation_pricing}}');
		
		$this->dropForeignKey("fk_quotation_itinerary_inquiry_package","quotation_itinerary");
        $this->dropTable('{{%quotation_itinerary}}');
    }
}
