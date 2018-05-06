<?php

namespace backend\components;

use backend\models\enums\UserTypes;
use Yii;
use yii\base\Component;
use common\models\Media;
use yii\web\UploadedFile;
use backend\models\enums\DirectoryTypes;

class LogoUploader extends Component
{
    public static function LogoUpload(UploadedFile $upFile,$image,$type=null, $id=null)
    {
        $media_model = new Media();
        $media_model->alt = $upFile->name;
        $itemName = md5(uniqid()) . '.' . $upFile->getExtension();  // unique_name+extension
        $media_model->file_name = $itemName;
        $media_model->file_size = $upFile->size;
        $media_model->file_type = $upFile->type;
        $media_model->media_type = $image;
        if ($media_model->save()) {}
            if(isset($type)) {
                if($type==UserTypes::ADMIN)
                    $upFile->saveAs(DirectoryTypes::getAdminDirectory(false) . $itemName);
                if($type==UserTypes::INQUIRY_HEAD)
                    $upFile->saveAs(DirectoryTypes::getInquiryHeadDirectory(false) . $itemName);
                if($type==UserTypes::QUOTATION_MANAGER)
                    $upFile->saveAs(DirectoryTypes::getQuotationManagerDirectory(false) . $itemName);
                if($type==UserTypes::FOLLOW_UP_MANAGER)
                    $upFile->saveAs(DirectoryTypes::getFollowUpManagerDirectory(false) . $itemName);
                else
                    $upFile->saveAs(DirectoryTypes::getStaffDirectory(false) . $itemName);
			}
            if(isset($id)) {
                $upFile->saveAs(DirectoryTypes::getPackageDirectory($id, false) . $itemName);
            }

        return $media_model->id;
    }
} 