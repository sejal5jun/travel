<?php

use yii\db\Schema;
use yii\db\Migration;

class m160613_112811_create_table_package extends Migration
{
    public function up()
    {
        $this->createTable('{{%package}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'itinerary' => $this->text(),
            'pricing' => $this->string(15),
            'terms_and_conditions' => $this->text(),
            'other_info' => $this->text(),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%package}}');
    }
}
