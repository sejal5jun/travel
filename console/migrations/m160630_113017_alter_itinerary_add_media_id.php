<?php

use yii\db\Migration;

class m160630_113017_alter_itinerary_add_media_id extends Migration
{
    public function up()
    {
		 $this->addColumn('itinerary', 'media_id', 'INT(11) AFTER description');
		 $this->addForeignKey('fk_itinerary_media', 'itinerary', 'media_id', 'media', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_itinerary_media', 'itinerary');
        $this->dropColumn('itinerary', 'media_id');
    }
	
}
