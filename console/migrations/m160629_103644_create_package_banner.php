<?php

use yii\db\Migration;

/**
 * Handles the creation for table `package_banner`.
 */
class m160629_103644_create_package_banner extends Migration
{
    public function up()
    {
        $this->createTable('{{%package_banner}}', [
            'id' => $this->primaryKey(),
            'package_id' => $this->integer(11),
            'media_id' => $this->integer(11),
            'status' => $this->smallInteger(6),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);

        $this->addForeignKey("fk_package_banner_package","package_banner","package_id","package","id","CASCADE","CASCADE");
        $this->addForeignKey("fk_package_banner_media","package_banner","media_id","media","id","CASCADE","CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk_package_banner_package","package_banner");
        $this->dropForeignKey("fk_package_banner_media","package_banner");
        $this->dropTable('{{%package_banner}}');
    }
}
