<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 20-08-2016
 * Time: 17:12
 */
namespace console\controllers;

use backend\models\enums\CronTypes;
use backend\models\enums\UserTypes;
use backend\models\enums\InquiryStatusTypes;
use common\models\Followup;
use common\models\User;
use common\models\Inquiry;
use common\models\RecordInquiry;
use common\models\ActivityCount;
use Yii;
use yii\console\Controller;

class InquiryReminderController extends Controller
{
    public function actionSend()
    {
        $email = [];
        $user = User::find()->where(['status' => User::STATUS_ACTIVE])->all();

        $adminInquiries = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->joinWith(['inquiryHead'])->all();
        foreach ($user as $u) {
            if($u->role == UserTypes::ADMIN) {
                $inquiries = $adminInquiries;
            } else {
                $inquiries = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                        ->andWhere(['or',['inquiry.quotation_manager' => $u->id],['inquiry.inquiry_head' => $u->id],['inquiry.quotation_staff' => $u->id],['inquiry.follow_up_head' => $u->id],['inquiry.follow_up_staff' => $u->id]])->joinWith(['inquiryHead'])->all();
            }
            //if($u->email == 'morepranit@gmail.com' || $u->email == 'pranit@fierydevs.com' ) {
                $total = count($inquiries);
                if($total > 0) {
                    $link = Yii::$app->urlManagerBackend->createAbsoluteUrl(['inquiry/index', 'type' => InquiryStatusTypes::IN_QUOTATION]);
                    $mailer = \Yii::$app->mailer->compose(['html' => 'pendingInquiries-html', 'text' => 'pendingInquiries-text'], ['user' => $u, 'link' => $link, 'inquiries' => $inquiries, 'total' => $total])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                        ->setTo($u->email)
                        ->setSubject('Pending Inquiries');
                    if ($mailer->send()) {
                        $email[] = $u->email;
                    }
                }
            //}
        }

        $len = count(($email));
        for ($y = 0; $y < $len; $y++) {
            Yii::$app->db->createCommand()->batchInsert('email_cron', ['type', 'email', 'status', 'created_at', 'updated_at'], [
                [CronTypes::PENDING_INQUIRY_REMAINDER, $email[$y], 10, time(), time()],
            ])->execute();
        }

    }

    public function actionSendInquiryReport() {
        $email = [];
        $user = User::find()->where(['status' => User::STATUS_ACTIVE, 'role' => UserTypes::ADMIN])->all();
        $start_date = strtotime('yesterday');//strtotime('Nov-03-2016');
        $end_date = strtotime('today');//strtotime('Nov-03-2016');
        $inquiryReports = RecordInquiry::find()->where(['between', 'date', $start_date, $end_date])->all();
        foreach ($user as $u) {
            //if($u->email == 'morepranit@gmail.com') {
                $total = count($inquiryReports);
                if($total > 0) {
                    $link = Yii::$app->urlManagerBackend->createAbsoluteUrl(['report/inquiry-report']);
                    $mailer = \Yii::$app->mailer->compose(['html' => 'yesterdaysInquiryReport-html', 'text' => 'yesterdaysInquiryReport-text'], ['user' => $u, 'link' => $link, 'inquiryReports' => $inquiryReports, 'total' => $total, 'yest_date' => $start_date])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                        ->setTo($u->email)
                        ->setSubject("Inquiry Report for " . date('d M Y', $start_date));
                    if ($mailer->send()) {
                        $email[] = $u->email;
                    }
                }
            //}
        }

        $len = count(($email));
        for ($y = 0; $y < $len; $y++) {
            Yii::$app->db->createCommand()->batchInsert('email_cron', ['type', 'email', 'status', 'created_at', 'updated_at'], [
                [CronTypes::YESTERDAYS_INQUIRY_REPORT, $email[$y], 10, time(), time()],
            ])->execute();
        }
    }

    public function actionSendPerformanceReport() {
        $email = [];
        $user = User::find()->where(['status' => User::STATUS_ACTIVE, 'role' => UserTypes::ADMIN])->all();
        $start_date = strtotime('yesterday');//strtotime('Nov-03-2016');
        $end_date = strtotime('today');//strtotime('Nov-03-2016');
        $performanceReports = ActivityCount::find()->where(['between', 'date', $start_date, $end_date])->all();
        foreach ($user as $u) {
            //if($u->email == 'morepranit@gmail.com') {
                $total = count($performanceReports);
                if($total > 0) {
                    $link = Yii::$app->urlManagerBackend->createAbsoluteUrl(['report/day-performance']);
                    $mailer = \Yii::$app->mailer->compose(['html' => 'yesterdaysPerformanceReport-html', 'text' => 'yesterdaysPerformanceReport-text'], ['user' => $u, 'link' => $link, 'performanceReports' => $performanceReports, 'total' => $total, 'yest_date' => $start_date])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                        ->setTo($u->email)
                        ->setSubject("Performance Report for " . date('d M Y', $start_date));
                    if ($mailer->send()) {
                        $email[] = $u->email;
                    }
                }
            //}
        }

        $len = count(($email));
        for ($y = 0; $y < $len; $y++) {
            Yii::$app->db->createCommand()->batchInsert('email_cron', ['type', 'email', 'status', 'created_at', 'updated_at'], [
                [CronTypes::YESTERDAYS_PERFORMANCE_REPORT, $email[$y], 10, time(), time()],
            ])->execute();
        }
    }
} 