<?php
namespace console\controllers;

use backend\models\enums\CronTypes;
use common\models\ScheduleFollowup;
use Yii;
use yii\console\Controller;

/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 18-08-2016
 * Time: 10:16
 */

class ScheduleFollowupController extends Controller{
    public function actionSend(){
        $type = [];
        $email = [];
        $now = time();
        $model = ScheduleFollowup::find()->where(['is_sent' => 0])
            ->andWhere('scheduled_at < :scheduled_at',[':scheduled_at' => $now])
            ->all();
        foreach($model as $follow){//echo $follow->id . "----" . $follow->passenger_email . "\n";continue;
            $mailer =  \Yii::$app->mailer->compose()
                ->setFrom($follow->scheduledBy->email)
                ->setTo($follow->passenger_email)
                ->setHtmlBody($follow->text_body);
            if(isset($follow->inquiry_package_id))
            {
                $mailer->setSubject('Inquiry Quotation/' . 'TA-' . $follow->inquiry->inquiry_id . '/' . $follow->inquiryPackage->passenger_name  . '/' .$follow->inquiryPackage->passenger_mobile . '/'. $follow->inquiryPackage->destination);
            }else
            {
                $mailer->setSubject('Destination: '. $follow->inquiry->destination);
            }

                //->setSubject('Inquiry Quotation/' . 'TA-' . $follow->inquiry->inquiry_id . '/' . isset($follow->inquiry_package_id)?$follow->inquiryPackage->passenger_name :'' . '/' . isset($follow->inquiry_package_id)?$follow->inquiryPackage->passenger_mobile:'' . '/'. isset($follow->inquiry_package_id)?$follow->inquiryPackage->destination:'');
               // ->setSubject('Inquiry Quotation/' . 'TA-' . $follow->inquiry->inquiry_id . '/' . isset($follow->inquiry_package_id)?$follow->inquiryPackage->passenger_name :'' . '/' . isset($follow->inquiry_package_id)?$follow->inquiryPackage->passenger_mobile:'' . '/'. isset($follow->inquiry_package_id)?$follow->inquiryPackage->destination:'');
                //->setSubject('Destination');
            /*$count = $mailer->send();
            echo "<pre>";print_r($mailer->toString());*/
            if($mailer->send()){
                $follow->is_sent = 1;
                $follow->save();

                $type[] = CronTypes::SCHEDULE_FOLLOWUP;
                $email[] = $follow->passenger_email;
            }
        }

        $len = count(($email));
        for ($y = 0; $y < $len; $y++) {
            Yii::$app->db->createCommand()->batchInsert('email_cron', ['type', 'email', 'status', 'created_at', 'updated_at'], [
                [$type[$y], $email[$y], 10, time(), time()],
            ])->execute();
        }

    }
}
