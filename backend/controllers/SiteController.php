<?php
namespace backend\controllers;

use backend\models\enums\CustomerTypes;
use backend\models\enums\InquiryPriorityTypes;
use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\UserTypes;
use common\models\Agent;
use common\models\Booking;
use common\models\Inquiry;
use common\models\Followup;
use common\models\Package;
use common\models\RecordLogin;
use Yii;
use common\filters\IpFilter;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => IpFilter::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'request-password-reset', 'forgot-password', 'reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        /* return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ]; */
    }

    public function actionIndex()
    {
        yii::$app->session->set('user_id',yii::$app->user->identity->id);
        $completed_inquiries_agent_maldives ='';
        $completed_inquiries_agent_mauritius= '';
        $completed_inquiries_customer_maldives= '';
        $completed_inquiries_customer_mauritius='';
        $cancelled_inquiries_customer_maldives='';
        $cancelled_inquiries_customer_mauritius= '';
        $cancelled_inquiries_agent_maldives= '';
        $cancelled_inquiries_agent_mauritius='';
        $all_followups_hi_customer_count ='';
        $all_followups_nhi_customer_count ='';
        $all_followups_hi_customer_maldives_count ='';
        $all_followups_nhi_customer_maldives_count ='';
        $all_followups_hi_customer_mauritius_count ='';
        $all_followups_nhi_customer_mauritius_count ='';
        $all_followups_hi_agent_count ='';
        $all_followups_nhi_agent_count ='';
        $all_followups_hi_agent_maldives_count ='';
        $all_followups_nhi_agent_maldives_count ='';
        $all_followups_hi_agent_mauritius_count ='';
        $all_followups_nhi_agent_mauritius_count ='';
        $all_pending_inquiries_hi_customer ='';
        $all_pending_inquiries_nhi_customer ='';
        $all_pending_inquiries_hi_customer_maldives ='';
        $all_pending_inquiries_nhi_customer_maldives ='';
        $all_pending_inquiries_hi_customer_mauritius ='';
        $all_pending_inquiries_nhi_customer_mauritius ='';
        $all_pending_inquiries_hi_agent ='';
        $all_pending_inquiries_nhi_agent ='';
        $all_pending_inquiries_hi_agent_maldives ='';
        $all_pending_inquiries_nhi_agent_maldives ='';
        $all_pending_inquiries_hi_agent_mauritius ='';
        $all_pending_inquiries_nhi_agent_mauritius ='';



        if (Yii::$app->user->identity->role == UserTypes::ADMIN) {

            $all_pending_inquiries = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::AGENT])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_hi_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::AGENT, 'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $all_pending_inquiries_nhi_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::AGENT, 'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $all_pending_inquiries_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_hi_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives', 'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $all_pending_inquiries_nhi_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives', 'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $all_pending_inquiries_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_hi_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius', 'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $all_pending_inquiries_nhi_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius', 'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $all_pending_inquiries_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::CUSTOMER])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_hi_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $all_pending_inquiries_hi_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $all_pending_inquiries_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_hi_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives','inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $all_pending_inquiries_nhi_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives','inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $all_pending_inquiries_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_hi_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius','inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $all_pending_inquiries_nhi_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius','inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $hot_new_inquiries_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT])->joinWith(['inquiryHead'])->all();
            $hot_new_inquiries_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->joinWith(['inquiryHead'])->all();
            $hot_new_inquiries_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->joinWith(['inquiryHead'])->all();
            $hot_new_inquiries_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->joinWith(['inquiryHead'])->all();
            $hot_new_inquiries_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->joinWith(['inquiryHead'])->all();
            $hot_new_inquiries_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' =>InquiryPriorityTypes::HOT_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority'  => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->joinWith(['inquiryHead'])->all();

            $pending_inquiries = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->joinWith(['inquiryHead'])->limit(5)->all();
            $pending_inquiries_count = count($all_pending_inquiries);


            $pending_inquiries_hi_count = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $pending_inquiries_nhi_count = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $completed_inquiries_agent = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => CustomerTypes::AGENT])->all();
            $completed_inquiries_agent_maldives = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->all();
            $completed_inquiries_agent_mauritius = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->all();
            $completed_inquiries_customer = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->all();
            $completed_inquiries_customer_maldives = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->all();
            $completed_inquiries_customer_mauritius = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->all();

            $cancelled_inquiries_agent = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => CustomerTypes::AGENT])->all();
            $cancelled_inquiries_agent_maldives = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->all();
            $cancelled_inquiries_agent_mauritius = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->all();
            $cancelled_inquiries_customer = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->all();
            $cancelled_inquiries_customer_maldives = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->all();
            $cancelled_inquiries_customer_mauritius = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->all();

            $today = strtotime('today');
            $all_todays_followups = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_todays_followups_agent = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_todays_followups_customer = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            

            $all_followups_customer = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_hi_customer_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER, 'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_nhi_customer_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER, 'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            

            $all_followups_agent = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_hi_agent_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT, 'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_nhi_agent_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT, 'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            

            $all_followups_customer_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_customer_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_hi_customer_maldives_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives', 'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_nhi_customer_maldives_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives', 'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_hi_customer_mauritius_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius', 'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_nhi_customer_mauritius_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius', 'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            

            $all_followups_agent_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_agent_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_hi_agent_maldives_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives', 'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_nhi_agent_maldives_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives', 'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_hi_agent_mauritius_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius', 'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_nhi_agent_mauritius_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius', 'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();




            $pending_followups_agent = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id')->all();
            $pending_followups_agent_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id')->all();
            $pending_followups_agent_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id')->all();
            $pending_followups_customer = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id')->all();
            $pending_followups_customer_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id')->all();
            $pending_followups_customer_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id')->all();

            $todays_followups_agent = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups_agent_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups_agent_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups_customer = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups_customer_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups_customer_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->limit(5)->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups_count = count($all_todays_followups);
        }
        else if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF)
        {

            $all_pending_inquiries = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_hi_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $all_pending_inquiries_nhi_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $all_pending_inquiries_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_hi_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $all_pending_inquiries_nhi_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $all_pending_inquiries_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_hi_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $all_pending_inquiries_nhi_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $all_pending_inquiries_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            

            $all_pending_inquiries_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_hi_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives', 'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->count();
            $all_pending_inquiries_nhi_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives', 'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->count();


            $all_pending_inquiries_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $all_pending_inquiries_hi_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $all_pending_inquiries_nhi_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $hot_new_inquiries_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_NEW_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::AGENT])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $hot_new_inquiries_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $hot_new_inquiries_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $hot_new_inquiries_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_NEW_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $hot_new_inquiries_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_NEW_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $hot_new_inquiries_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_NEW_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::AGENT])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $hot_old_inquiries_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::HOT_OLD_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::AGENT])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_new_inquiries_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_agent = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::AGENT])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_agent_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_agent_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_customer = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_customer_maldives = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();
            $general_old_inquiries_customer_mauritius = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER,  'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->all();

            $pending_inquiries = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['quotation_manager' => Yii::$app->user->identity->id])->joinWith(['inquiryHead'])->all();
            $pending_inquiries_count = count($all_pending_inquiries);

            $pending_inquiries_hi_count = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['quotation_manager' => Yii::$app->user->identity->id, 'inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();
            $pending_inquiries_nhi_count = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['quotation_manager' => Yii::$app->user->identity->id, 'inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiryHead'])->count();

            $completed_inquiries_agent = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]]) ->all();
            $completed_inquiries_agent_maldives = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])
                 ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]]) ->all();
            $completed_inquiries_agent_mauritius = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])
               ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]]) ->all();

            $completed_inquiries_customer = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]]) ->all();
            $completed_inquiries_customer_maldives = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])
               ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]]) ->all();
            $completed_inquiries_customer_mauritius = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])
               ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]]) ->all();

            $cancelled_inquiries_agent = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]]) ->all();
            $cancelled_inquiries_agent_maldives = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])
            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]]) ->all();
            $cancelled_inquiries_agent_mauritius = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])
            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]]) ->all();
             $cancelled_inquiries_customer = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->all();
            $cancelled_inquiries_customer_maldives = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->all();
            $cancelled_inquiries_customer_mauritius = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])
              ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->all();


            $today = strtotime('today');
            $all_todays_followups = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();

            $all_followups_customer = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_hi_customer_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->andWhere(['inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();

            $all_followups_nhi_customer_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();


            $all_followups_agent = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_hi_agent_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_nhi_agent_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();

            $all_followups_customer_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_hi_customer_maldives_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_nhi_customer_maldives_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();


            $all_followups_customer_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_hi_customer_mauritius_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_nhi_customer_mauritius_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();


            $all_followups_agent_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_hi_agent_maldives_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_nhi_agent_maldives_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();


            $all_followups_agent_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $all_followups_hi_agent_mauritius_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::HIGHLY_INTERESTED])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();
            $all_followups_nhi_agent_mauritius_count = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->andWhere(['followup.is_followup' => 0])->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->andWhere(['inquiry.highly_interested' => Inquiry::NOT_HIGHLY_INTERESTED])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->count();


            $pending_followups_agent = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $pending_followups_agent_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $pending_followups_agent_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $pending_followups_customer = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $pending_followups_customer_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $pending_followups_customer_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();

            //$todays_followups = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere(['inquiry.quotation_manager' => Yii::$app->user->identity->id])->limit(5)->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();

            $todays_followups_agent = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups_agent_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups_agent_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups_customer = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups_customer_maldives = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();
            $todays_followups_customer_mauritius = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->andWhere([ 'inquiry.customer_type' => CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])
                ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->all();

            $todays_followups_count = count($all_todays_followups);

        }

        if (Yii::$app->user->identity->role == UserTypes::BOOKING_STAFF) {
            $all_bookings = Booking::find()->where(['booking_staff' => Yii::$app->user->identity->id])->all();
            $all_bookings_agent = Booking::find()->where(['booking_staff' => Yii::$app->user->identity->id,'inquiry.customer_type'=>CustomerTypes::AGENT])->joinWith(['inquiry'])->all();
            $all_bookings_agent_maldives = Booking::find()->where(['booking_staff' => Yii::$app->user->identity->id,'inquiry.customer_type'=>CustomerTypes::AGENT,'inquiry.destination'=>'maldives'])->joinWith(['inquiry'])->all();
            $all_bookings_agent_mauritius = Booking::find()->where(['booking_staff' => Yii::$app->user->identity->id,'inquiry.customer_type'=>CustomerTypes::AGENT,'inquiry.destination'=>'mauritius'])->joinWith(['inquiry'])->all();
            $all_bookings_customer = Booking::find()->where(['booking_staff' => Yii::$app->user->identity->id,'inquiry.customer_type'=>CustomerTypes::CUSTOMER])->joinWith(['inquiry'])->all();
            $all_bookings_customer_maldives = Booking::find()->where(['booking_staff' => Yii::$app->user->identity->id,'inquiry.customer_type'=>CustomerTypes::CUSTOMER,'inquiry.destination'=>'maldives'])->joinWith(['inquiry'])->all();
            $all_bookings_customer_mauritius = Booking::find()->where(['booking_staff' => Yii::$app->user->identity->id,'inquiry.customer_type'=>CustomerTypes::CUSTOMER,'inquiry.destination'=>'mauritius'])->joinWith(['inquiry'])->all();
            return $this->render('index', ['all_bookings' => $all_bookings,
                'all_bookings_agent' => $all_bookings_agent,
                'all_bookings_agent_maldives' => $all_bookings_agent_maldives,
                'all_bookings_agent_mauritius' => $all_bookings_agent_mauritius,
                'all_bookings_customer' => $all_bookings_customer,
                'all_bookings_customer_maldives' => $all_bookings_customer_maldives,
                'all_bookings_customer_mauritius' => $all_bookings_customer_mauritius,
            ]);
        }

        if (Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF) {
            return $this->render('index', [
                //'pending_inquiries' => $pending_inquiries,
                'todays_followups_agent' => $todays_followups_agent,
                'todays_followups_customer' => $todays_followups_customer,
                //'todays_followups' => $todays_followups,
                'pending_followups_agent' => $pending_followups_agent,
                'pending_followups_customer' => $pending_followups_customer,
                //'pending_inquiries_count' => $pending_inquiries_count,
                //'todays_followups_count' => $todays_followups_count,
                'all_pending_inquiries_agent' => $all_pending_inquiries_agent,
                'all_pending_inquiries_customer' => $all_pending_inquiries_customer,
                'hot_new_inquiries_agent' => $hot_new_inquiries_agent,
                'hot_new_inquiries_customer' => $hot_new_inquiries_customer,
                'hot_old_inquiries_agent' => $hot_old_inquiries_agent,
                'hot_old_inquiries_customer' => $hot_old_inquiries_customer,
                'general_new_inquiries_agent' => $general_new_inquiries_agent,
                'general_new_inquiries_customer' => $general_new_inquiries_customer,
                'general_old_inquiries_agent' => $general_old_inquiries_agent,
                'general_old_inquiries_customer' => $general_old_inquiries_customer,
                'completed_inquiries_agent' => $completed_inquiries_agent,
                'completed_inquiries_customer' => $completed_inquiries_customer,
                'cancelled_inquiries_customer' => $cancelled_inquiries_customer,
                'cancelled_inquiries_agent' => $cancelled_inquiries_agent,

                'todays_followups_agent_maldives' => $todays_followups_agent_maldives,
                'todays_followups_agent_mauritius' => $todays_followups_agent_mauritius,
                'todays_followups_customer_maldives' => $todays_followups_customer_maldives,
                'todays_followups_customer_mauritius' => $todays_followups_customer_mauritius,

                'pending_followups_agent_maldives' => $pending_followups_agent_maldives,
                'pending_followups_agent_mauritius' => $pending_followups_agent_mauritius,
                'pending_followups_customer_maldives' => $pending_followups_customer_maldives,
                'pending_followups_customer_mauritius' => $pending_followups_customer_mauritius,

                'all_followups_customer' => $all_followups_customer,
                'all_followups_agent' => $all_followups_agent,
                'all_followups_customer_maldives' => $all_followups_customer_maldives,
                'all_followups_customer_mauritius' => $all_followups_customer_mauritius,
                'all_followups_agent_maldives' => $all_followups_agent_maldives,
                'all_followups_agent_mauritius' => $all_followups_agent_mauritius,

                'pending_inquiries_hi_count' => $pending_inquiries_hi_count,
                'pending_inquiries_nhi_count' => $pending_inquiries_nhi_count,
                'all_pending_inquiries_hi_customer_maldives' => $all_pending_inquiries_hi_customer_maldives,
                'all_pending_inquiries_nhi_customer_maldives' => $all_pending_inquiries_nhi_customer_maldives,
                'all_pending_inquiries_hi_customer_mauritius' => $all_pending_inquiries_hi_customer_mauritius,
                'all_pending_inquiries_nhi_customer_mauritius' => $all_pending_inquiries_nhi_customer_mauritius,

                'all_pending_inquiries_hi_agent' => $all_pending_inquiries_hi_agent,
                'all_pending_inquiries_hi_agent_maldives' => $all_pending_inquiries_hi_agent_maldives,
                'all_pending_inquiries_hi_agent_mauritius' => $all_pending_inquiries_hi_agent_mauritius,
                'all_pending_inquiries_nhi_agent' => $all_pending_inquiries_nhi_agent,
                'all_pending_inquiries_nhi_agent_maldives' => $all_pending_inquiries_nhi_agent_maldives,
                'all_pending_inquiries_nhi_agent_mauritius' => $all_pending_inquiries_nhi_agent_mauritius,

                'all_followups_hi_customer_count' => $all_followups_hi_customer_count,
                'all_followups_nhi_customer_count' => $all_followups_nhi_customer_count,
                'all_followups_hi_customer_maldives_count' => $all_followups_hi_customer_maldives_count,
                'all_followups_nhi_customer_maldives_count' => $all_followups_nhi_customer_maldives_count,
                'all_followups_hi_customer_mauritius_count' => $all_followups_hi_customer_mauritius_count,
                'all_followups_nhi_customer_mauritius_count' => $all_followups_nhi_customer_mauritius_count,

                'all_followups_hi_agent_count' => $all_followups_hi_agent_count,
                'all_followups_nhi_agent_count' => $all_followups_nhi_agent_count,
                'all_followups_hi_agent_maldives_count' => $all_followups_hi_agent_maldives_count,
                'all_followups_nhi_agent_maldives_count' => $all_followups_nhi_agent_maldives_count,
                'all_followups_hi_agent_mauritius_count' => $all_followups_hi_agent_mauritius_count,
                'all_followups_nhi_agent_mauritius_count' => $all_followups_nhi_agent_mauritius_count,

                'all_pending_inquiries_agent_maldives' => $all_pending_inquiries_agent_maldives,
                'all_pending_inquiries_agent_mauritius' => $all_pending_inquiries_agent_mauritius,
                'all_pending_inquiries_customer_maldives' => $all_pending_inquiries_customer_maldives,
                'all_pending_inquiries_customer_mauritius' => $all_pending_inquiries_customer_mauritius,
                'hot_new_inquiries_agent_maldives' => $hot_new_inquiries_agent_maldives,
                'hot_new_inquiries_agent_mauritius' => $hot_new_inquiries_agent_mauritius,
                'hot_new_inquiries_customer_maldives' => $hot_new_inquiries_customer_maldives,
                'hot_new_inquiries_customer_mauritius' => $hot_new_inquiries_customer_mauritius,
                'hot_old_inquiries_agent_maldives' => $hot_old_inquiries_agent_maldives,
                'hot_old_inquiries_agent_mauritius' => $hot_old_inquiries_agent_mauritius,
                'hot_old_inquiries_customer_maldives' => $hot_old_inquiries_customer_maldives,
                'hot_old_inquiries_customer_mauritius' => $hot_old_inquiries_customer_mauritius,
                'general_new_inquiries_agent_maldives' => $general_new_inquiries_agent_maldives,
                'general_new_inquiries_agent_mauritius' => $general_new_inquiries_agent_mauritius,
                'general_new_inquiries_customer_maldives' => $general_new_inquiries_customer_maldives,
                'general_new_inquiries_customer_mauritius' => $general_new_inquiries_customer_mauritius,
                'general_old_inquiries_agent_maldives' => $general_old_inquiries_agent_maldives,
                'general_old_inquiries_agent_mauritius' => $general_old_inquiries_agent_mauritius,
                'general_old_inquiries_customer_maldives' => $general_old_inquiries_customer_maldives,
                'general_old_inquiries_customer_mauritius' => $general_old_inquiries_customer_mauritius,
                'completed_inquiries_agent_maldives' => $completed_inquiries_agent_maldives,
                'completed_inquiries_agent_mauritius' => $completed_inquiries_agent_mauritius,
                'completed_inquiries_customer_maldives' => $completed_inquiries_customer_maldives,
                'completed_inquiries_customer_mauritius' => $completed_inquiries_customer_mauritius,
                'cancelled_inquiries_customer_maldives' => $cancelled_inquiries_customer_maldives,
                'cancelled_inquiries_customer_mauritius' => $cancelled_inquiries_customer_mauritius,
                'cancelled_inquiries_agent_maldives' => $cancelled_inquiries_agent_maldives,
                'cancelled_inquiries_agent_mauritius' => $cancelled_inquiries_agent_mauritius,
            ]);
        }
    }

    public function actionLogin()
    {
        $this->layout = "login";
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $record_login = new RecordLogin();
            $record_login->user_id = Yii::$app->user->identity->id;
            $record_login->login_time = time();
            // $record_login->login_time = strtotime(date("Y-m-d H:i:s"));
            $record_login->save();
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        $record_login = new RecordLogin();
        $record_login->user_id = Yii::$app->user->identity->id;
        $record_login->logout_time = time();
        //$record_login->logout_time = strtotime(date("Y-m-d H:i:s"));
        $record_login->save();
        Yii::$app->user->logout();

        return $this->redirect(['login']);
        //return $this->goHome();
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        $name = $exception->statusCode;
        $message = $exception->getMessage();
        if ($exception !== null) {
            if (!Yii::$app->user->isGuest) {
                return $this->render('error', ['exception' => $exception, 'name' => $name, 'message' => $message]);
            } else {
                $this->layout = "error";
                return $this->render('error_without_login', ['exception' => $exception, 'name' => $name, 'message' => $message]);
            }
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = "login";
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = "login";
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
