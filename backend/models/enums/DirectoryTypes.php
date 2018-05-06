<?php 
namespace backend\models\enums;

use Yii;
use yii\helpers\Url;

class DirectoryTypes {
   const UPLOADS = 0;

    public static $folderName = array(
        self::UPLOADS => 'uploads',
       
    );

    public static function getAdminDirectory($is_relative=true)
    {
        if(!$is_relative){
            return Url::to(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR.Yii::$app->params['uploadDir'] . 'admin' . DIRECTORY_SEPARATOR) ;
        }
        else {
            return Url::to(\Yii::getAlias('@web') . "/" . Yii::$app->params['uploadDir']  . 'admin'  . '/', true);
        }
    }

    public static function getInquiryCollectorDirectory($is_relative=true)
    {
        if(!$is_relative){
            return Url::to(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR.Yii::$app->params['uploadDir'] . 'inquiry_collector' . DIRECTORY_SEPARATOR) ;
        }
        else {
            return Url::to(\Yii::getAlias('@web') . "/" . Yii::$app->params['uploadDir']  . 'inquiry_collector' . '/', true);
        }
    }


    public static function getInquiryHeadDirectory($is_relative=true)
    {
        if(!$is_relative){
            return Url::to(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR.Yii::$app->params['uploadDir'] . 'inquiry_head' . DIRECTORY_SEPARATOR) ;
        }
        else {
            return Url::to(\Yii::getAlias('@web') . "/" . Yii::$app->params['uploadDir']  . 'inquiry_head' . '/', true);
        }
    }

    public static function getQuotationManagerDirectory($is_relative=true)
    {
        if(!$is_relative){
            return Url::to(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR.Yii::$app->params['uploadDir'] . 'quotation_manager' . DIRECTORY_SEPARATOR) ;
        }
        else {
            return Url::to(\Yii::getAlias('@web') . "/" . Yii::$app->params['uploadDir']  . 'quotation_manager' . '/', true);
        }
    }

    public static function getFollowUpManagerDirectory($is_relative=true)
    {
        if(!$is_relative){
            return Url::to(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR.Yii::$app->params['uploadDir'] . 'follow_up_manager' . DIRECTORY_SEPARATOR) ;
        }
        else {
            return Url::to(\Yii::getAlias('@web') . "/" . Yii::$app->params['uploadDir']  . 'follow_up_manager' . '/', true);
        }
    }

    public static function getStaffDirectory($is_relative=true)
    {
        if(!$is_relative){
            return Url::to(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR.Yii::$app->params['uploadDir'] . 'staff' . DIRECTORY_SEPARATOR) ;
        }
        else {
            return Url::to(\Yii::getAlias('@web') . "/" . Yii::$app->params['uploadDir']  . 'staff' . '/', true);
        }
    }

    public static function getLogoDirectory($dirType,$is_relative=true)
    {
        if(!$is_relative){
            return Url::to(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR.Yii::$app->params['uploadDir']) ;
        }
        else {
            return Url::to(\Yii::getAlias('@web') . "/" . Yii::$app->params['uploadDir'] , true);
        }
    }

    public static function getPackageDirectory($packageId, $is_relative=true)
    {
        if(!$is_relative){
            return Url::to(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR.Yii::$app->params['uploadDir']. 'package' . DIRECTORY_SEPARATOR.$packageId. DIRECTORY_SEPARATOR) ;
        }
        else {
            return Url::to(\Yii::getAlias('@web') . "/" . Yii::$app->params['uploadDir']  . 'package' . '/' . $packageId . '/', true);
        }
    }

} 
?>