<?php

use yii\db\Migration;

class m161129_091555_alter_table_inquiry_add_column_highly_interested extends Migration
{
    public function up()
    {
         $this->addColumn('inquiry', 'highly_interested', 'TINYINT(1) DEFAULT 0 AFTER status');
    }

    public function down()
    {
        $this->dropColumn('inquiry', 'highly_interested');
    }
}
