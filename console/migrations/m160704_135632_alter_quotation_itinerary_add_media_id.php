<?php

use yii\db\Migration;

class m160704_135632_alter_quotation_itinerary_add_media_id extends Migration
{
    public function up()
    {
		 $this->addColumn('quotation_itinerary', 'media_id', 'INT(11) AFTER description');
		 $this->addForeignKey('fk_quotation_itinerary_media', 'quotation_itinerary', 'media_id', 'media', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
       $this->dropForeignKey('fk_quotation_itinerary_media', 'quotation_itinerary');
       $this->dropColumn('quotation_itinerary', 'media_id');
    }

}
