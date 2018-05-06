<?php

use yii\db\Migration;

class m160629_114524_alter_package_add_category_sub_title extends Migration
{
    public function up()
    {
        $this->addColumn('package', 'sub_title', 'VARCHAR(45) AFTER name');
        $this->addColumn('package', 'category', 'INT(11) AFTER sub_title');
        $this->addColumn('package', 'package_include', 'TEXT AFTER pricing');
        $this->addColumn('package', 'package_exclude', 'TEXT AFTER package_include');
		
		$this->dropColumn('package', 'itinerary');
        $this->dropColumn('package', 'pricing');
    }

    public function down()
    {
        $this->dropColumn('package', 'package_include');
        $this->dropColumn('package', 'package_exclude');
        $this->dropColumn('package', 'sub_title');
        $this->dropColumn('package', 'category');
    }
}
