<?php

use yii\db\Migration;

/**
 * Handles the creation for table `package_pricing`.
 */
class m160629_112414_create_package_pricing extends Migration
{
     public function up()
    {
        $this->createTable('{{%package_pricing}}', [
            'id' => $this->primaryKey(),
            'package_id' => $this->integer(11),
            'currency_id' => $this->integer(11),
            'type' =>$this->integer(11),
            'price' =>$this->string(45),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);

        $this->addForeignKey("fk_package_pricing_package","package_pricing","package_id","package","id","CASCADE","CASCADE");
        $this->addForeignKey("fk_package_pricing_currency","package_pricing","currency_id","currency","id","CASCADE","CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk_package_pricing_inquiry","package_pricing");
        $this->dropForeignKey("fk_package_pricing_currency","package_pricing");
        $this->dropTable('{{%package_pricing}}');
    }
}
