<?php

use yii\db\Migration;

class m160702_105431_alter_itinerary_package_inquiry extends Migration
{
    public function up()
    {
        $this->renameColumn('itinerary','no_of_days','no_of_itineraries');
        $this->renameColumn('package','sub_title','no_of_days_nights');
    }

    public function down()
    {

    }

}
