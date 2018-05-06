<?php

use yii\db\Migration;

class m160822_074013_alter_inquiry_add_reference_name extends Migration
{
    public function up()
    {
		$this->addColumn('inquiry', 'reference', 'VARCHAR(255) AFTER source');
    }

    public function down()
    {
        $this->dropColumn('inquiry', 'reference');
    }
}
