<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 20-08-2016
 * Time: 17:12
 */
namespace console\controllers;


use backend\models\enums\InquiryStatusTypes;
use common\models\Inquiry;
use Yii;
use yii\console\Controller;

class BookingArchiveController extends Controller
{
    public function actionArchive()
    {
       $time = time();
         $booked_inquiry_model = Inquiry::find()->where(['status'=>InquiryStatusTypes::COMPLETED])->all();
            foreach($booked_inquiry_model as $bim_value)
            {
               if($bim_value->from_date < $time)
                {
                  $bim_value->status = InquiryStatusTypes::ARCHIVED;

                  $bim_value->save();

                }

            }

    }
}