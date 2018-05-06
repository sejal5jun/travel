<?php

use yii\db\Migration;

/**
 * Handles the creation for table `itinerary`.
 */
class m160629_111346_create_itinerary extends Migration
{
    public function up()
    {
        $this->createTable('{{%itinerary}}', [
            'id' => $this->primaryKey(),
            'package_id' => $this->integer(11),
            'name' => $this->text(),
            'no_of_days' =>$this->integer(11),
            'title' =>$this->string(255),
            'description' =>$this->text(),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);

        $this->addForeignKey("fk_itinerary_package","itinerary","package_id","package","id","CASCADE","CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk_itinerary_inquiry","itinerary");
        $this->dropTable('{{%itinerary}}');
    }
}
