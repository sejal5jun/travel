<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 21-07-2016
 * Time: 15:25
 */

namespace console\controllers;

use backend\models\enums\CronTypes;
use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\UserTypes;
use common\models\Followup;
use common\models\User;
use Yii;
use yii\console\Controller;

class FollowupReminderController extends Controller
{
    public function actionSend()
    {
        $date = strtotime('today');
        $id = [];
        $email = [];

        $followup = Followup::find()->where(['date' => $date])->all();
        foreach ($followup as $f) {
            $id[] = $f->id;
        }
        $model = Followup::find()->where(['IN', 'id', $id])->all();

        foreach ($model as $follow) {
            $link = Yii::$app->urlManagerBackend->createAbsoluteUrl(['inquiry/quoted-inquiry', 'id' => $follow->inquiry_id]);
            if($follow->inquiry->follow_up_staff!=''){
                $mailer = \Yii::$app->mailer->compose()
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($follow->inquiry->followUpStaff->email)
                    ->setHtmlBody("<div class='followup-remainder'>
                                    <p>Dear " . $follow->inquiry->followUpStaff->username . ",</p>
                                    <p>You have scheduled Follow Up for Inquiry TA- " . $follow->inquiry->inquiry_id . ".</p>
                                    <p>For more details <a href='" . $link . "'>click here</a></p>
                                </div>")
                    ->setSubject('Follow Up Reminder/' . 'TA-' . $follow->inquiry->inquiry_id . '/' . $follow->inquiryPackage->passenger_name . '/' . $follow->inquiryPackage->passenger_mobile . '/'. $follow->inquiryPackage->destination);
                // ->setSubject('Follow Up Reminder: ' . 'TA-' . $follow->inquiry->inquiry_id);
                if ($mailer->send()) {
                    $email[] = $follow->inquiry->followUpStaff->email;
                }
            }
            $mailer = \Yii::$app->mailer->compose()
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                ->setTo($follow->by0->email)
                ->setHtmlBody("<div class='followup-remainder'>
                                    <p>Dear " . $follow->by0->username . ",</p>
                                    <p>You have scheduled Follow Up for Inquiry TA- " . $follow->inquiry->inquiry_id . ".</p>
                                    <p>For more details <a href='" . $link . "'>click here</a></p>
                                </div>")
                ->setSubject('Follow Up Reminder/' . 'TA-' . $follow->inquiry->inquiry_id . '/' . $follow->inquiryPackage->passenger_name . '/' . $follow->inquiryPackage->passenger_mobile . '/'. $follow->inquiryPackage->destination);
               // ->setSubject('Follow Up Reminder: ' . 'TA-' . $follow->inquiry->inquiry_id);
            if ($mailer->send()) {
                $email[] = $follow->by0->email;
            }
        }
        $len = count(($email));
        for ($y = 0; $y < $len; $y++) {
            Yii::$app->db->createCommand()->batchInsert('email_cron', ['type', 'email', 'status', 'created_at', 'updated_at'], [
                [CronTypes::DAILY_FOLLOWUP_REMAINDER, $email[$y], 10, time(), time()],
            ])->execute();
        }
    }

    public function actionSendPendingFollowups() {
        $email = [];
        $today = strtotime('today');
        $user = User::find()->where(['status' => User::STATUS_ACTIVE])->all();

        $adminFollowUps = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id')->all();
        foreach ($user as $u) {
            if($u->role == UserTypes::ADMIN) {
                $followUps = $adminFollowUps;
            } else {
                $followUps = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                    ->andWhere(['or',['inquiry.quotation_manager' => $u->id],['inquiry.inquiry_head' => $u->id],['inquiry.quotation_staff' => $u->id],['inquiry.follow_up_head' => $u->id],['inquiry.follow_up_staff' => $u->id]])
                    ->joinWith(['inquiry.inquiryHead', 'inquiry'])
                    ->groupBy('inquiry.id')->all();
            }
            //if($u->email == 'morepranit@gmail.com' || $u->email == 'pranit@fierydevs.com' ) {
                $total = count($followUps);
                if($total > 0) {
                    echo $total . "\n";
                    $link = Yii::$app->urlManagerBackend->createAbsoluteUrl(['inquiry/index', 'type' => InquiryStatusTypes::QUOTED]);
                    $mailer = \Yii::$app->mailer->compose(['html' => 'pendingFollowUps-html', 'text' => 'pendingFollowUps-text'], ['user' => $u, 'link' => $link, 'followUps' => $followUps, 'total' => $total])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                        ->setTo($u->email)
                        ->setSubject('Pending Follow Ups');
                    if ($mailer->send()) {
                        $email[] = $u->email;
                    }
                }
            //}
        }

        $len = count(($email));
        for ($y = 0; $y < $len; $y++) {
            Yii::$app->db->createCommand()->batchInsert('email_cron', ['type', 'email', 'status', 'created_at', 'updated_at'], [
                [CronTypes::PENDING_FOLLOWUP_REMINDER, $email[$y], 10, time(), time()],
            ])->execute();
        }
    }
}