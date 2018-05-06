<?php

use yii\db\Migration;

class m160805_110225_alter_package_add_itinerary_name extends Migration
{
     public function up()
    {
      $this->addColumn('package', 'itinerary_name', 'VARCHAR(255) AFTER no_of_days_nights');
      $this->addColumn('inquiry_package', 'itinerary_name', 'VARCHAR(255) AFTER package_name');
    }

    public function down()
    {
        $this->dropColumn('package', 'itinerary_name');
        $this->dropColumn('inquiry_package', 'itinerary_name');
    }
}
