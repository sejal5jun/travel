<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 23-12-2015
 * Time: 19:06
 */
namespace backend\components;
use yii\base\Component;
use backend\models\enums\DirectoryTypes;

class DirectoryCreator extends Component {

    public static function createPackageDirectory($packageId)
    {
        $dir_path = DirectoryTypes::getPackageDirectory($packageId,false);
        if(!is_dir($dir_path))
        {
            mkdir($dir_path,0777,true);
        }
    }

    public static function deletePackageDirectory($packageId)
    {
        $dir_path = DirectoryTypes::getPackageDirectory($packageId,false);
        if(!is_dir($dir_path))
        {
            self::rrmdir($dir_path);
        }
    }

    private static function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir")
                        rrmdir($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}