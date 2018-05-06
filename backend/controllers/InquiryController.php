<?php

namespace backend\controllers;

use backend\components\LogoUploader;
use backend\models\enums\ActivityTypes;
use backend\models\enums\CustomerTypes;
use backend\models\enums\DirectoryTypes;
use backend\models\enums\InquiryActivityTypes;
use backend\models\enums\InquiryPriorityTypes;
use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\InquiryTypes;
use backend\models\enums\MediaTypes;
use backend\models\enums\UserTypes;
use common\models\ActivityCount;
use common\models\Agent;
use common\models\Booking;
use common\models\City;
use common\models\Country;
use common\models\Followup;
use common\models\InquiryActivity;
use common\models\InquiryChildAge;
use common\models\InquiryPackage;
use common\models\InquiryPackageChildAge;
use common\models\InquiryPackageCity;
use common\models\InquiryPackageCountry;
use common\models\InquiryPackageRoomType;
use common\models\InquiryRoomType;
use common\models\Itinerary;
use common\models\Package;
use common\models\PackagePricing;
use common\models\PriceType;
use common\models\PackageSearch;
use common\models\QuotationItinerary;
use common\models\QuotationPricing;
use common\models\RecordActivity;
use common\models\RecordBooking;
use common\models\RecordInquiry;
use common\models\RoomType;
use common\models\ScheduleFollowup;
use common\models\User;
use PHPExcel;
use PHPExcel_IOFactory;
use Yii;
use common\models\Inquiry;
use common\models\InquirySearch;
use common\filters\IpFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * InquiryController implements the CRUD actions for Inquiry model.
 */
class InquiryController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => IpFilter::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],


                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Inquiry models.
     * @params $type
     * @return mixed
     */
    public function actionIndex($type = "", $priority = "", $f_type = "",$customer_type="",$country="")
    {

      /* $model = Inquiry::find()->where(['id'=>13])->one();

        Yii::$app->set('mailer',[
                'class' => 'yii\swiftmailer\Mailer',
                'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => 'smtp.gmail.com',
                    'username' => 'marketing.portal101@gmail.com',
                    'password' => 'marketingportal101',
                    'port' => '587',
                    'encryption' => 'tls',
                ],
            ]

            );*/
       /* \Yii::$app->mailer->compose()
            ->setFrom('sejalgupta48@gmail.com')
            ->setTo('sejal@fierydevs.com')

            ->setSubject('testing')
            ->send();
        exit;*/
        // echo '<pre>';print_r(Yii::$app->request->get());exit;
        $model = new Inquiry();
        $followup_model = new Followup();
        $booking_model = new Booking();
        $follow_up_type = [
            ['id' => InquirySearch::ALL_FOLLOWUPS, 'name' => 'All Followups'],
            ['id' => InquirySearch::PENDING_FOLLOWUPS, 'name' => 'Pending Followups'],
            ['id' => InquirySearch::TODAYS_FOLLOWUPS, 'name' => 'Today\'s Followups']

        ];

        $export = Yii::$app->request->get('export');
        $search = Yii::$app->request->get('search');
        if (isset($export) && !isset($search)) {
            $this->Export($type);
        }

        $followup_type = ArrayHelper::map($follow_up_type, 'id', 'name');

        if (isset(Yii::$app->request->queryParams['InquirySearch']['followup_type']))
            $f_type = Yii::$app->request->queryParams['InquirySearch']['followup_type'];

        $searchModel = $dataProvider = $searchModel_pending = $dataProvider_pending = $searchModel_quoted = $dataProvider_quoted = $searchModel_completed = $dataProvider_completed = $searchModel_cancelled = $dataProvider_cancelled = '';
        $searchModel_vouchered = '';
        $dataProvider_vouchered ='';
        if ($type != "") {
            if ($customer_type != "" && $priority != null && $type == InquiryStatusTypes::IN_QUOTATION && $country != "") {
                $searchModel_pending = new InquirySearch();
                $dataProvider_pending = $searchModel_pending->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::IN_QUOTATION, "", $priority, $customer_type, $country);
                $dataProvider_pending->pagination->pageSize = 75;
            }
            else if ($customer_type != "" && $country == "" && $priority != null && $type == InquiryStatusTypes::IN_QUOTATION) {
                $searchModel_pending = new InquirySearch();
                $dataProvider_pending = $searchModel_pending->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::IN_QUOTATION, "", $priority, $customer_type);
                $dataProvider_pending->pagination->pageSize = 75;
            }
            else if ($customer_type != "" && $country != "" && $priority == "") {
                $searchModel_quoted = new InquirySearch();
                $dataProvider_quoted = $searchModel_quoted->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::QUOTED, $f_type, "", $customer_type, $country);
                $dataProvider_quoted->pagination->pageSize = 75;

                $searchModel_pending = new InquirySearch();
                $dataProvider_pending = $searchModel_pending->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::IN_QUOTATION, "", "", $customer_type, $country);
                $dataProvider_pending->pagination->pageSize = 75;


                $searchModel_completed = new InquirySearch();
                $dataProvider_completed = $searchModel_completed->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::COMPLETED, "", "", $customer_type, $country);
                $dataProvider_completed->pagination->pageSize = 75;

                $searchModel_cancelled = new InquirySearch();
                $dataProvider_cancelled = $searchModel_cancelled->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::CANCELLED, "", "", $customer_type, $country);
                $dataProvider_cancelled->pagination->pageSize = 75;

            }
            else if ($customer_type != "" && $country == "" && $priority == "") {

                $searchModel_quoted = new InquirySearch();
                $dataProvider_quoted = $searchModel_quoted->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::QUOTED, $f_type, "", $customer_type);
                $dataProvider_quoted->pagination->pageSize = 75;

                $searchModel_pending = new InquirySearch();
                $dataProvider_pending = $searchModel_pending->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::IN_QUOTATION, "", "", $customer_type);
                $dataProvider_pending->pagination->pageSize = 75;


                $searchModel_completed = new InquirySearch();
                $dataProvider_completed = $searchModel_completed->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::COMPLETED, "", "", $customer_type);
                $dataProvider_completed->pagination->pageSize = 75;

                $searchModel_cancelled = new InquirySearch();
                $dataProvider_cancelled = $searchModel_cancelled->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::CANCELLED, "", "", $customer_type);
                $dataProvider_cancelled->pagination->pageSize = 75;
            }
            else {
                if ($type == InquiryStatusTypes::QUOTED){
                $searchModel_quoted = new InquirySearch();
                $dataProvider_quoted = $searchModel_quoted->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::QUOTED, $f_type);
                $dataProvider_quoted->pagination->pageSize = 75;
            }
                   if($type == InquiryStatusTypes::IN_QUOTATION){
                       $searchModel_pending = new InquirySearch();
                       $dataProvider_pending = $searchModel_pending->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::IN_QUOTATION);
                       $dataProvider_pending->pagination->pageSize = 75;
                   }
                if($type == InquiryStatusTypes::COMPLETED){
                    $searchModel_completed = new InquirySearch();
                    $dataProvider_completed = $searchModel_completed->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::COMPLETED);
                    $dataProvider_completed->pagination->pageSize = 75;
                }
                if($type == InquiryStatusTypes::CANCELLED){
                    $searchModel_cancelled = new InquirySearch();
                    $dataProvider_cancelled = $searchModel_cancelled->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::CANCELLED);
                    $dataProvider_cancelled->pagination->pageSize = 75;

                }
                if($type == InquiryStatusTypes::VOUCHERED){

                    $searchModel_vouchered = new InquirySearch();
                    $dataProvider_vouchered = $searchModel_vouchered->search(Yii::$app->request->queryParams, $search_criteria = InquiryStatusTypes::VOUCHERED);
                    $dataProvider_vouchered->pagination->pageSize = 75;

                }
            }
        }
        else {

            $searchModel = new InquirySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->pagination->pageSize = 75;
        }

        //echo count($dataProvider_quoted);exit;
        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModel_pending' => $searchModel_pending,
            'dataProvider_pending' => $dataProvider_pending,
            'searchModel_quoted' => $searchModel_quoted,
            'dataProvider_quoted' => $dataProvider_quoted,
            'searchModel_completed' => $searchModel_completed,
            'dataProvider_completed' => $dataProvider_completed,
            'searchModel_cancelled' => $searchModel_cancelled,
            'dataProvider_cancelled' => $dataProvider_cancelled,
            'searchModel_vouchered' => $searchModel_vouchered,
            'dataProvider_vouchered' => $dataProvider_vouchered,
            'followup_model' => $followup_model,
            'type' => $type,
            'followup_type' => $followup_type,
            'f_type' => $f_type,
            'priority' => $priority,
        ]);
    }


    /**
     * Displays a single Inquiry model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $activity = '')
    {

        $model = $this->findModel($id);
       // print_r($model->bookings[0]->inquiryPackage->package); exit;
        $inquiry_quotation = new InquiryPackage();
        $status = $model->status;
        $type = $model->status;
        $new_followup_model = new Followup();
        $new_inquiry_model = new Inquiry();
        $new_booking_model = new Booking();

        $new_inq_package_model='';

        $booking_model = Booking::find()->where(['inquiry_id'=>$model->id])->one();
        if(count($booking_model) > 0) {

            $new_inq_package_model = InquiryPackage::find()->where(['id' => $booking_model->inquiry_package_id])->one();

        }
        $followup_model = Followup::find()->where(['inquiry_id' => $model->id])->orderBy(['id' => SORT_DESC])->all();

        if ($type == InquiryStatusTypes::AMENDED)
            $type = InquiryStatusTypes::IN_QUOTATION;
        $roomtypes = '';
        if (isset($model->inquiryRoomTypes)) {
            foreach ($model->inquiryRoomTypes as $room) {
                $roomtypes .= $room->roomType->type;
                $roomtypes .= ',';
            }
            $roomtypes = rtrim($roomtypes, ',');
        }

        $model->from_date = date('Y-m-d', $model->from_date);
        $model->return_date = date('Y-m-d', $model->return_date);

        $age_model = new InquiryChildAge();
        $agent_model = new Agent();
        $city = City::getCity();
        $city_name = '';
        if ($model->customer_type == CustomerTypes::AGENT) {
            $city_name = $model->agent->city_id;
        }
        //  echo $city_name;exit;

        $child_age = [];
        $child = InquiryChildAge::find()->where(['inquiry_id' => $id])->all();
        foreach ($child as $val) {

            $child_age[] = $val->age;
        }

        $room_type_id = [];
        $room_arr = [];
        $room_type_id_arr = InquiryRoomType::find()->where(['inquiry_id' => $model->id])->all();

        foreach ($room_type_id_arr as $val) {
            $room_arr[] = $val->roomType->type;
        }
        $room = RoomType::getRoomTypes();

        if(count($booking_model) > 0){
        if ($new_inq_package_model->load(Yii::$app->request->post())) {
                   // echo '<pre>'; print_r($new_inq_package_model); exit;
            $new_inq_package_model->save();
            return $this->render('view', [
                'model' => $model,
                'roomtypes' => $roomtypes,
                'age_model' => $age_model,
                'agent_model' => $agent_model,
                'city' => $city,
                'room_arr' => $room_arr,
                'child_age' => $child_age,
                'city_name' => $city_name,
                'type' => $type,
                'activity' => $activity,
                'followup_model' => $followup_model,
                'inquiry_quotation' => $inquiry_quotation,
                'new_followup_model'=>$new_followup_model,

                'new_inquiry_model'=>$new_inquiry_model,
                'new_booking_model'=>$new_booking_model,
                'new_inq_package_model'=>$new_inq_package_model,

            ]);
        }
        }


        if ($agent_model->load(Yii::$app->request->post())) {

            if (!in_array($agent_model->city_id, $city, true)) {
                $city_model = new City();
                $city_model->name = $agent_model->city_id;
                if ($city_model->save()) {
                    $agent_model->city_id = $city_model->id;
                }
            } else {
                $city_id = City::find()->where(['name' => $agent_model->city_id])->one();
                $agent_model->city_id = $city_id->id;
                $agent_model->save();
            }
        }

          if (Yii::$app->request->post('inquiry_id') != '') {

              if ($new_inquiry_model->load(Yii::$app->request->post())) {


            $inquiry_model = $this->findModel(Yii::$app->request->post('inquiry_id'));
            $inquiry_model->status = $new_inquiry_model->status;
            $inquiry_model->notes = $new_inquiry_model->notes;
            if ($inquiry_model->save()) {

                if ($inquiry_model->status == InquiryStatusTypes::AMENDED) {

                    $inq_package_all = InquiryPackage::find()->where(['inquiry_id' => $id])->all();
                    foreach ($inq_package_all as $quotations) {
                        $quotations->status = InquiryPackage::STATUS_DELETED;
                        $quotations->save();
                    }
                    $this->sendMail($model, $inquiry_model->status);
                    $type = InquiryStatusTypes::IN_QUOTATION;

                    /////////////// record acivity
                    $record_activity = new RecordActivity();
                    $record_activity->inquiry_id = $id;
                    $record_activity->activity = ActivityTypes::AMENDED;
                    $record_activity->created_by = Yii::$app->user->identity->id;
                    $record_activity->status = 10;
                    $record_activity->notes = $inquiry_model->notes;
                    $record_activity->save();



                    Yii::$app->getSession()->setFlash('success', 'Inquiry status changed successfully.');
                    return $this->redirect(['index', 'type' => $type]);
                }
                else if($inquiry_model->status == InquiryStatusTypes::CANCELLED)
                {

                    $type = InquiryStatusTypes::CANCELLED;

                    /////////////// record acivity
                    $record_activity = new RecordActivity();
                    $record_activity->inquiry_id = $id;
                    $record_activity->activity = ActivityTypes::CANCELLED;
                    $record_activity->created_by = Yii::$app->user->identity->id;
                    $record_activity->status = 10;
                    $record_activity->notes = $inquiry_model->notes;
                    $record_activity->save();



                    Yii::$app->getSession()->setFlash('success', 'Inquiry status changed successfully.');
                    return $this->redirect(['index', 'type' => $type]);
                }
                else if($inquiry_model->status == InquiryStatusTypes::VOUCHERED)
                {
                    $booking_model = Booking::find()->where(['inquiry_id'=>$inquiry_model->id])->one();
                    if ($new_booking_model->load(Yii::$app->request->post())) {
                        $booking_model->voucher_currency_id = $new_booking_model->voucher_currency_id;
                        $booking_model->voucher_inr_rate = $new_booking_model->voucher_inr_rate;
                        $booking_model->voucher_final_price = $new_booking_model->voucher_final_price;
                        $booking_model->save();
                    }

                    $type = InquiryStatusTypes::VOUCHERED;

                    /////////////// record acivity
                    $record_activity = new RecordActivity();
                    $record_activity->inquiry_id = $id;
                    $record_activity->activity = ActivityTypes::VOUCHERED;
                    $record_activity->created_by = Yii::$app->user->identity->id;
                    $record_activity->status = 10;
                    $record_activity->notes = $inquiry_model->notes;
                    $record_activity->save();



                    Yii::$app->getSession()->setFlash('success', 'Inquiry status changed successfully.');
                    return $this->redirect(['index', 'type' => $type]);

                }







            }

        }
          }



        //add quotation details
        if ($inquiry_quotation->load(Yii::$app->request->post())) {
            // echo '<pre>';print_r($inquiry_quotation->hotel_details);exit;
            $inquiry_quotation->inquiry_id = $id;
            $inquiry_quotation->passenger_name = $model->name;
            $inquiry_quotation->passenger_email = $model->email;
            $inquiry_quotation->passenger_mobile = $model->mobile;
            $inquiry_quotation->destination = $model->destination;
            $inquiry_quotation->leaving_from = $model->leaving_from;
            $inquiry_quotation->adult_count = $model->adult_count;
            $inquiry_quotation->children_count = $model->children_count;
            $inquiry_quotation->room_count = $model->room_count;
            $inquiry_quotation->from_date = strtotime($model->from_date);
            $inquiry_quotation->return_date = strtotime($model->return_date);
            $inquiry_quotation->no_of_nights = $model->no_of_days;
            $inquiry_quotation->notes = $model->notes;
            $inquiry_quotation->inquiry_details = $model->inquiry_details;
            $inquiry_quotation->package_id = null;
            $inquiry_quotation->is_quotation_sent = InquiryPackage::QUOTATION_NOT_SENT;
            if ($model->type != InquiryTypes::PER_ROOM_PER_NIGHT) {
                $inquiry_quotation->hotel_details = null;
            } else {
                $inquiry_quotation->quotation_details = null;
            }
            if ($inquiry_quotation->save()) {
                $quotation_id = $inquiry_quotation->id;

                /////////////// room-type
                if ($inquiry_quotation->room_count != '' || $inquiry_quotation->room_count != 0) {
                    $inquiry_room = InquiryRoomType::find()->where(['inquiry_id' => $id])->all();
                    foreach ($inquiry_room as $room) {
                        $quotation_room = new InquiryPackageRoomType();
                        $quotation_room->inquiry_package_id = $quotation_id;
                        $quotation_room->room_type_id = $room->room_type_id;
                        $quotation_room->save();
                    }
                }

                /////////////// child-age
                if ($inquiry_quotation->children_count != '' || $inquiry_quotation->children_count != 0) {
                    $inquiry_age = InquiryChildAge::find()->where(['inquiry_id' => $id])->all();
                    foreach ($inquiry_age as $age) {
                        $quotation_age = new InquiryPackageChildAge();
                        $quotation_age->inquiry_package_id = $quotation_id;
                        $quotation_age->age = $age->age;
                        $quotation_age->save();
                    }
                }

                /////////////// Follow-up
                $follow_up = new Followup();
                $follow_up->date = strtotime('today') + 86400;
                $follow_up->by = $model->follow_up_head;
                $follow_up->inquiry_package_id = $quotation_id;
                $follow_up->inquiry_id = $id;
                $follow_up->note = "First Follow Up";
                $follow_up->status = 10;
                $follow_up->is_followup = 0;
                $follow_up->save();


                //////////////// change status of inquiry to quoted
                $inquiry_model = $this->findModel($id);
                $inquiry_model->status = InquiryStatusTypes::QUOTED;
                $inquiry_model->save();

                /////////////// record acivity
                $record_activity = new RecordActivity();
                $record_activity->inquiry_id = $id;
                $record_activity->activity = ActivityTypes::QUOTED;
                $record_activity->created_by = Yii::$app->user->identity->id;
                $record_activity->status = 10;
                $record_activity->save();

                $inquiry_activity = new InquiryActivity();
                $inquiry_activity->inquiry_id = $id;
                $inquiry_activity->user_id = Yii::$app->user->identity->id;
                $inquiry_activity->date = strtotime('today');
                $inquiry_activity->type = InquiryActivityTypes::QUOTED;

                if ($inquiry_activity->save()) {
                    $activity_count = ActivityCount::find()->where(['user_id' => $inquiry_activity->user_id, 'date' => $inquiry_activity->date])->one();
                    if (count($activity_count) > 0) {
                        $quotation_count = $activity_count->quotation_count;
                        $activity_count->quotation_count = $quotation_count + 1;
                        $activity_count->save();

                    } else {
                        $activity_count = new ActivityCount();
                        $activity_count->user_id = Yii::$app->user->identity->id;
                        $activity_count->date = strtotime('today');
                        $activity_count->quotation_count = 1;
                        $activity_count->followup_count = 0;
                        $activity_count->save();
                    }

                    $record_activity_count = RecordInquiry::find()->where(['date' => strtotime('today')])->one();
                    if (count($record_activity_count) > 0) {
                        $quotation_count = $record_activity_count->quotation_count;
                        $record_activity_count->quotation_count = $quotation_count + 1;
                        $record_activity_count->save();

                    } else {
                        $record_activity_count = new RecordInquiry();
                        $record_activity_count->date = strtotime('today');
                        $record_activity_count->new_inquiry_count = 0;
                        $record_activity_count->quotation_count = 1;
                        $record_activity_count->followup_count = 0;
                        $record_activity_count->booking_count = 0;
                        $record_activity_count->cancellation_count = 0;
                        $record_activity_count->save();
                    }
                }
                // echo '<pre>';print_r($follow_up->getErrors());exit;
                Yii::$app->getSession()->setFlash('success', 'Quotation details added successfully.');
                return $this->redirect(['quoted-inquiry', 'id' => $id]);
            } else {
                // echo '<pre> ';print_r($inquiry_quotation->getErrors());exit;
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if ($model->load(Yii::$app->request->post()) || $age_model->load(Yii::$app->request->post())) {
           // echo 'enter'; exit;
            $room_type = [];
            $room_type = Yii::$app->request->post("room_type");
            $room_num = count(Yii::$app->request->post("room_type"));

            if (isset($room_type)) {
                $room_diff = array_diff($room_type, $room);
                $room_same = array_intersect($room_type, $room);

                if (count($room_diff) > 0) {

                    foreach ($room_diff as $rt) {
                        $room_model = new RoomType();
                        $room_model->type = $rt;
                        $room_model->save();
                        $room_type_id[] = $room_model->id;
                    }
                } else {

                    $room_id = RoomType::find()->where(['IN', 'type', $room_same])->all();

                    foreach ($room_id as $rid) {
                        $room_type_id[] = $rid->id;
                    }

                }
            }

            // $model->mobile = preg_replace('/(\D+)/', '', $model->mobile);
            $model->status = $status;
            $model->from_date = strtotime($model->from_date);
            $model->return_date = strtotime($model->return_date);
            if ($model->customer_type == CustomerTypes::CUSTOMER) {
                $model->agent_id = '';
            }

            if ($model->save()) {

                InquiryChildAge::deleteAll('inquiry_id = :inquiry_id', [':inquiry_id' => $id]);
                InquiryRoomType::deleteAll('inquiry_id = :inquiry_id', [':inquiry_id' => $id]);
                //room type

                for ($i = 0; $i < $room_num; $i++) {
                    $inq_room_type = new InquiryRoomType();
                    $inq_room_type->inquiry_id = $id;
                    $inq_room_type->room_type_id = $room_type_id[$i];
                    $inq_room_type->save();
                }

// children count
                if (isset($age_model->age)) {
                    foreach ($age_model->age as $val) {
                        $child_model = new InquiryChildAge();
                        $child_model->inquiry_id = $model->id;
                        $child_model->age = $val;
                        $child_model->save();
                    }
                }

                $activity = new RecordActivity();
                $activity->inquiry_id = $model->id;
                $activity->activity = ActivityTypes::UPDATED;
                $activity->created_by = Yii::$app->user->identity->id;
                $activity->status = 10;
                $activity->save();

                Yii::$app->getSession()->setFlash('info', 'Inquiry is updated successfully.');

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                //echo '<pre>'; print_r($model->getErrors()); exit;
                return $this->render('view', [
                    'model' => $model,
                    'roomtypes' => $roomtypes,
                    'age_model' => $age_model,
                    'agent_model' => $agent_model,
                    'city' => $city,
                    'room_arr' => $room_arr,
                    'child_age' => $child_age,
                    'city_name' => $city_name,
                    'type' => $type,
                    'activity' => $activity,
                    'followup_model' => $followup_model,
                    'inquiry_quotation' => $inquiry_quotation,
                    'new_followup_model'=>$new_followup_model,
                    'booking_model'=>$booking_model,
                    'new_inquiry_model'=>$new_inquiry_model,
                    'new_booking_model'=>$new_booking_model,
                    'new_inq_package_model'=>$new_inq_package_model,
                ]);
            }
        } else {

            return $this->render('view', [
                'model' => $model,
                'roomtypes' => $roomtypes,
                'age_model' => $age_model,
                'agent_model' => $agent_model,
                'city' => $city,
                'room_arr' => $room_arr,
                'child_age' => $child_age,
                'city_name' => $city_name,
                'type' => $type,
                'activity' => $activity,
                'followup_model' => $followup_model,
                'inquiry_quotation' => $inquiry_quotation,
                'new_followup_model'=>$new_followup_model,
                'booking_model'=>$booking_model,
                'new_inquiry_model'=>$new_inquiry_model,
                'new_booking_model'=>$new_booking_model,
                'new_inq_package_model'=>$new_inq_package_model,


            ]);
        }
    }

    public function actionQuotedInquiry($id, $quotation_no = '', $quotation_id='',$quote_id='')
    {
        $model = $this->findModel($id);
        $followup = Followup::find()->where(['inquiry_id' => $model->id])->orderBy(['id' => SORT_DESC])->all();
        $new_model = new Inquiry();
        $pop_inquiry_model = new inquiry();
        $followup_model = new Followup();
        $booking_model = new Booking();
        $schedule_followup = new ScheduleFollowup();
        $is_schedule = 0;
        $schedule_followup_model = ScheduleFollowup::find()->where(['inquiry_id' => $id, 'is_sent' => 0])->all();
        if (count($schedule_followup_model) > 0) {
            $is_schedule = 1;
        }



        if ($quotation_no == '' && $quotation_id == '') {

            /*  $inq_package_all = InquiryPackage::find()->where(['inquiry_id' => $id])->orderBy(['id' => SORT_DESC])->all();
              $inq_package = $inq_package_all[0];*/
            $inq_package = InquiryPackage::find()->where(['inquiry_id' => $id])->andWhere(['!=', 'status', InquiryPackage::STATUS_DELETED])->orWhere(['inquiry_id' => $id,'status' => null])->orderBy(['id' => SORT_DESC])->all();
            if($quote_id != '') {

               if($inq_package[$quote_id]->load(Yii::$app->request->post())) {


                    $inq_package[$quote_id]->save();

               }
            }


            if(count($inq_package)==1 ){
                $inq_package = $inq_package[0];
            }
        } else if ($quotation_no != ''){
            $inq_package_all = InquiryPackage::find()->where(['inquiry_id' => $id])->all();
            $inq_package = $inq_package_all[$quotation_no];
        }
        else if ($quotation_id != ''){
            $inq_package = InquiryPackage::findOne($quotation_id);
            if($inq_package->load(Yii::$app->request->post())) {
               $inq_package->save();

            }
        }

        //schedule followup
        if ($schedule_followup->load(Yii::$app->request->post())) {

            $inq_package_id = Yii::$app->request->post('schedule_inquiry_package_id');
            $schedule_followup->inquiry_id = $id;
            $schedule_followup->inquiry_package_id = $inq_package_id;
            $package_inquiry = InquiryPackage::findOne($inq_package_id);
            $schedule_followup->passenger_email = $package_inquiry->passenger_email;
            $scheduled_at = Yii::$app->request->post('schedule_date') . Yii::$app->request->post('schedule_time');
            $scheduled_at = strtotime($scheduled_at);
            $schedule_followup->scheduled_at = $scheduled_at;
            $schedule_followup->scheduled_by = Yii::$app->user->identity->id;
            if ($schedule_followup->save()) {
                //add record
                $followup_activity = new RecordActivity();
                $followup_activity->inquiry_id = $id;
                $followup_activity->activity = ActivityTypes::SCHEDULED_MAIL;
                $followup_activity->notes = "Scheduled Followup Mail at " . date("M-d-Y h:i a",$scheduled_at);
                $followup_activity->created_by = Yii::$app->user->identity->id;
                $followup_activity->status = 10;
                $followup_activity->save();

                //add followup
                $followup = Followup::find()->where(['inquiry_id' => $id])->all();
                foreach ($followup as $f) {
                    $f->is_followup = 1;
                    $f->save();
                }
                $in_pack = InquiryPackage::find()->where(['inquiry_id' => $id])->orderBy(['id' => 'desc'])->all();
                $s_followup = new Followup();
                $s_followup->date = strtotime(Yii::$app->request->post('schedule_date'));
                $s_followup->inquiry_package_id = $in_pack[0]->id;
                $s_followup->by = $model->follow_up_head;
                $s_followup->inquiry_id = $id;
                $s_followup->note = "Scheduled Followup Mail at " .  date("M-d-Y h:i a",$scheduled_at);
                //print_r($s_followup); exit;
                $s_followup->save();
                Yii::$app->getSession()->setFlash('success', 'Followup Mail scheduled successfully.');
                return $this->redirect(['quoted-inquiry', 'id' => $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'Followup Mail not scheduled.');
                return $this->redirect(['quoted-inquiry', 'id' => $id]);
            }
        }

        if ($new_model->load(Yii::$app->request->post()) || $model->load(yii::$app->request->post())) {
                  //echo Yii::$app->request->post('inquiry_id'); exit;
            //echo'<pre>'; print_r($model); exit;

            if(Yii::$app->request->post('inquiry_id')=='' &&  $model->load(yii::$app->request->post())) {

                $model->save();
                return $this->redirect(['quoted-inquiry', 'id' => $id]);
            }
            else {

                $inquiry_model = $this->findModel(Yii::$app->request->post('inquiry_id'));

                $inquiry_model->status = $new_model->status;
                $inquiry_model->notes = $new_model->notes;

                if ($inquiry_model->save()) {
                    $followup = Followup::find()->where(['inquiry_id' => $inquiry_model->id])->all();
                    foreach ($followup as $f) {
                        $f->is_followup = 1;
                        $f->save();
                    }

                    //followup details

                    $in_pack = InquiryPackage::find()->where(['inquiry_id' => $inquiry_model->id])->orderBy(['id' => 'desc'])->all();

                    if ($inquiry_model->status == InquiryStatusTypes::COMPLETED) {

                        $booking_model->load(Yii::$app->request->post());
                        //print_r($booking_model); exit;
                        $booking = Booking::find()->orderBy(['id' => SORT_ASC])->all();
                        $count = count($booking);
                        if ($count > 0)
                            $last_id = $booking[$count - 1]->booking_id;
                        else
                            $last_id = 0;

                        $booking_model->booking_id = "KRB-" . ($last_id[4] + 1);
                        $booking_model->inquiry_id = $inquiry_model->id;
                        $booking_model->save();
                        // echo '<pre>';print_r($booking_model->getErrors());exit;
                        $month_year = strtotime(date('M Y'));
                        $record_booking = RecordBooking::find()->where(['month_year' => $month_year])->andWhere(['user_id' => $booking_model->booking_staff])->one();
                        if (count($record_booking) > 0) {
                            $amount = (float)$record_booking->amount;
                            $rate = (float)$booking_model->inr_rate;
                            $price = (float)$booking_model->final_price;
                            $record_booking->amount = (string)($amount + $rate * $price);
                            $record_booking->save();

                        } else {
                            $record_booking = new RecordBooking();
                            $record_booking->user_id = $booking_model->booking_staff;
                            $rate = (float)$booking_model->inr_rate;
                            $price = (float)$booking_model->final_price;
                            $record_booking->amount = (string)($rate * $price);
                            $record_booking->month_year = $month_year;
                            $record_booking->save();
                        }
                    }


                    if ($followup_model->load(Yii::$app->request->post())) {

                        if ($followup_model->date != "") {
                            //echo'<pre>'; print_r($in_pack); exit;
                            $followup_model->date = strtotime($followup_model->date);
                            $followup_model->inquiry_package_id = $in_pack[0]->id;
                            $followup_model->by = $inquiry_model->follow_up_head;
                            $followup_model->inquiry_id = $inquiry_model->id;
                            $followup_model->note = $new_model->notes;

                            //print_r($followup_model); exit;
                            $followup_model->save();
                        }
                    }
                    //record inquiry
                    $status = '';
                    if ($inquiry_model->status == InquiryStatusTypes::QUOTED)
                        $status = ActivityTypes::FOLLOWED_UP;
                    if ($inquiry_model->status == InquiryStatusTypes::AMENDED)
                        $status = ActivityTypes::AMENDED;
                    if ($inquiry_model->status == InquiryStatusTypes::COMPLETED)
                        $status = ActivityTypes::COMPLETED;
                    if ($inquiry_model->status == InquiryStatusTypes::CANCELLED)
                        $status = ActivityTypes::CANCELLED;

                    if ($status == ActivityTypes::FOLLOWED_UP) {
                        $followup_activity = new RecordActivity();
                        $followup_activity->inquiry_id = $inquiry_model->id;
                        $followup_activity->activity = ActivityTypes::FOLLOWED_UP;
                        $followup_activity->notes = $new_model->notes;
                        $followup_activity->created_by = Yii::$app->user->identity->id;
                        $followup_activity->status = 10;
                        $followup_activity->save();
                    } else {
                        $followup_activity = new RecordActivity();
                        $followup_activity->inquiry_id = $inquiry_model->id;
                        $followup_activity->activity = ActivityTypes::FOLLOWED_UP;
                        $followup_activity->notes = $new_model->notes;
                        $followup_activity->created_by = Yii::$app->user->identity->id;
                        $followup_activity->status = 10;
                        $followup_activity->save();

                        $activity = new RecordActivity();
                        $activity->inquiry_id = $inquiry_model->id;
                        $activity->activity = $status;
                        $activity->notes = $new_model->notes;
                        $activity->created_by = Yii::$app->user->identity->id;
                        $activity->status = 10;
                        $activity->save();
                    }

                    //send mail if inquiry is amended/change status of all quotations
                    if ($inquiry_model->status == InquiryStatusTypes::AMENDED) {
                        $inq_package_all = InquiryPackage::find()->where(['inquiry_id' => $id])->all();
                        foreach ($inq_package_all as $quotations) {
                            $quotations->status = InquiryPackage::STATUS_DELETED;
                            $quotations->save();
                        }
                        $this->sendMail($model, $inquiry_model->status);
                    }


                    //add record to report(inquiry_activity/activity_count/record_inquiry)

                    $inquiry_activity = new InquiryActivity();
                    $inquiry_activity->inquiry_id = $id;
                    $inquiry_activity->user_id = Yii::$app->user->identity->id;
                    $inquiry_activity->date = strtotime('today');
                    $inquiry_activity->type = InquiryActivityTypes::FOLLOWUP;
                    if ($inquiry_activity->save()) {
                        $activity_count = ActivityCount::find()->where(['user_id' => $inquiry_activity->user_id, 'date' => $inquiry_activity->date])->one();
                        if (count($activity_count) > 0) {
                            $followup_count = $activity_count->followup_count;
                            $activity_count->followup_count = $followup_count + 1;
                            $activity_count->save();

                        } else {
                            $activity_count = new ActivityCount();
                            $activity_count->user_id = Yii::$app->user->identity->id;
                            $activity_count->date = strtotime('today');
                            $activity_count->quotation_count = 0;
                            $activity_count->followup_count = 1;
                            $activity_count->save();
                        }

                        if ($inquiry_model->status == InquiryStatusTypes::QUOTED || $inquiry_model->status == InquiryStatusTypes::AMENDED) {
                            $record_activity_count = RecordInquiry::find()->where(['date' => $inquiry_activity->date])->one();
                            if (count($record_activity_count) > 0) {
                                $followup_count = $record_activity_count->followup_count;
                                $record_activity_count->followup_count = $followup_count + 1;
                                $record_activity_count->save();

                            } else {
                                $record_activity_count = new RecordInquiry();
                                $record_activity_count->date = strtotime('today');
                                $record_activity_count->new_inquiry_count = 0;
                                $record_activity_count->quotation_count = 0;
                                $record_activity_count->followup_count = 1;
                                $record_activity_count->booking_count = 0;
                                $record_activity_count->cancellation_count = 0;
                                $record_activity_count->save();
                            }
                        } else if ($inquiry_model->status == InquiryStatusTypes::COMPLETED) {
                            $record_activity_count = RecordInquiry::find()->where(['date' => $inquiry_activity->date])->one();
                            if (count($record_activity_count) > 0) {
                                $followup_count = $record_activity_count->followup_count;
                                $booking_count = $record_activity_count->booking_count;
                                $record_activity_count->followup_count = $followup_count + 1;
                                $record_activity_count->booking_count = $booking_count + 1;
                                $record_activity_count->save();

                            } else {
                                $record_activity_count = new RecordInquiry();
                                $record_activity_count->date = strtotime('today');
                                $record_activity_count->new_inquiry_count = 0;
                                $record_activity_count->quotation_count = 0;
                                $record_activity_count->followup_count = 1;
                                $record_activity_count->booking_count = 1;
                                $record_activity_count->cancellation_count = 0;
                                $record_activity_count->save();
                            }

                        } else if ($inquiry_model->status == InquiryStatusTypes::CANCELLED) {
                            $record_activity_count = RecordInquiry::find()->where(['date' => $inquiry_activity->date])->one();
                            if (count($record_activity_count) > 0) {
                                $followup_count = $record_activity_count->followup_count;
                                $cancellation_count = $record_activity_count->cancellation_count;
                                $record_activity_count->followup_count = $followup_count + 1;
                                $record_activity_count->cancellation_count = $cancellation_count + 1;
                                $record_activity_count->save();

                            } else {
                                $record_activity_count = new RecordInquiry();
                                $record_activity_count->date = strtotime('today');
                                $record_activity_count->new_inquiry_count = 0;
                                $record_activity_count->quotation_count = 0;
                                $record_activity_count->followup_count = 1;
                                $record_activity_count->booking_count = 0;
                                $record_activity_count->cancellation_count = 1;
                                $record_activity_count->save();
                            }
                        }
                    }

                    $type = $inquiry_model->status;
                    //send mail if inquiry is amended/change status of all quotations
                    if ($inquiry_model->status == InquiryStatusTypes::AMENDED) {
                        $inq_package_all = InquiryPackage::find()->where(['inquiry_id' => $id])->all();
                        foreach ($inq_package_all as $quotation) {
                            $quotation->status = InquiryPackage::STATUS_DELETED;
                            $quotation->save();
                        }
                        $this->sendMail($model, $inquiry_model->status);
                        $type = InquiryStatusTypes::IN_QUOTATION;
                    }

                    Yii::$app->getSession()->setFlash('success', 'Inquiry status changed successfully.');
                    return $this->redirect(['index', 'type' => $type]);
                } else {
                    return $this->render('quoted_inquiry_view', [
                        'model' => $model,
                        'new_model' => $new_model,
                        'inq_package' => $inq_package,
                        'followup_model' => $followup_model,
                        'followup' => $followup,
                        'booking_model' => $booking_model,
                        'schedule_followup' => $schedule_followup,
                        'is_schedule' => $is_schedule,
                        'quotation_no' => $quotation_no,
                        'pop_inquiry_model' => $pop_inquiry_model,
                    ]);
                }

            }

        }


        else {
            return $this->render('quoted_inquiry_view', [
                'model' => $model,
                'new_model' => $new_model,
                'inq_package' => $inq_package,
                'followup_model' => $followup_model,
                'followup' => $followup,
                'booking_model' => $booking_model,
                'schedule_followup' => $schedule_followup,
                'is_schedule' => $is_schedule,
                'quotation_no' => $quotation_no,
                'pop_inquiry_model' => $pop_inquiry_model,
            ]);

        }


    }

    public function actionMarkHighlyInterested($id, $highly_interested) {
        $model = $this->findModel($id);

        if ($highly_interested == Inquiry::HIGHLY_INTERESTED || $highly_interested == Inquiry::NOT_HIGHLY_INTERESTED) {
            $model->highly_interested = $highly_interested;
            if ($model->save()) {
                echo "success";
            } else {
                echo "failed";
            }
        } else {
            echo "Invalid Input.";
        }
    }

    public function actionHighlyInterested()
    {
        $ids = Yii::$app->request->post('ids');

        Inquiry::UpdateAll(['highly_interested'=>1],['id'=>$ids]);

    }
    /**
     * Creates a new Inquiry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Inquiry();
        $age_model = new InquiryChildAge();
        $agent_model = new Agent();
        $city = City::getCity();
        $room_type_id = '';
        $room_arr = [];
        $child_age = [];
        $city_name = '';
        $room = RoomType::getRoomTypes();

        if ($agent_model->load(Yii::$app->request->post())) {
            if (!in_array($agent_model->city_id, $city, true)) {
                $city_model = new City();
                $city_model->name = $agent_model->city_id;
                if ($city_model->save()) {
                    $agent_model->city_id = $city_model->id;
                }
            } else {
                $city_id = City::find()->where(['name' => $agent_model->city_id])->one();
                $agent_model->city_id = $city_id->id;
                $agent_model->save();
            }
        } else if(Yii::$app->request->isAjax) {

        }
        if ($model->load(Yii::$app->request->post()) && $age_model->load(Yii::$app->request->post())) {

            //print_r($age_model->age); exit;
            $room_type = [];
            $room_type = Yii::$app->request->post("room_type");
            $room_num = count(Yii::$app->request->post("room_type"));
             if (isset($room_type)) {
                $room_diff = array_diff($room_type, $room);
                $room_same = array_intersect($room_type, $room);

                if (count($room_diff) > 0) {

                    foreach ($room_diff as $rt) {
                        $room_model = new RoomType();
                        $room_model->type = $rt;
                        $room_model->save();
                        $room_type_id[] = $room_model->id;
                    }
                } else {

                    $room_id = RoomType::find()->where(['IN', 'type', $room_same])->all();

                    foreach ($room_id as $rid) {
                        $room_type_id[] = $rid->id;
                    }

                }
            }

            // $model->mobile = preg_replace('/(\D+)/', '', $model->mobile);
            $model->status = InquiryStatusTypes::IN_QUOTATION;
            $model->from_date = strtotime($model->from_date);
            $model->return_date = strtotime($model->return_date);
            $model->created_by = yii::$app->user->identity->id;
            $inquiry = Inquiry::find()->orderBy(['id' => SORT_ASC])->all();
            $count = count($inquiry);
            if ($count > 0)
                $last_id = $inquiry[$count - 1]->inquiry_id;
            else
                $last_id = 0;
            $model->inquiry_id = $last_id + 1;

            if ($model->customer_type == CustomerTypes::CUSTOMER) {
                $model->agent_id = '';
            }
            if ($model->save()) {
//room type
                $id = $model->id;

                for ($i = 0; $i < $room_num; $i++) {
                    $inq_room_type = new InquiryRoomType();
                    $inq_room_type->inquiry_id = $id;
                    $inq_room_type->room_type_id = $room_type_id[$i];
                    $inq_room_type->save();
                }

// children count
                if (isset($age_model->age)) {
                    foreach ($age_model->age as $val) {
                        $child_model = new InquiryChildAge();
                        $child_model->inquiry_id = $model->id;
                        $child_model->age = $val;
                        $child_model->save();
                    }
                }
                //send mail
                $this->sendMail($model);

                // record inquiry/activity
                $activity = new RecordActivity();
                $activity->inquiry_id = $model->id;
                $activity->activity = ActivityTypes::ADDED;
                $activity->created_by = Yii::$app->user->identity->id;
                $activity->status = 10;
                $activity->save();

                $record_activity_count = RecordInquiry::find()->where(['date' => strtotime('today')])->one();
                if (count($record_activity_count) > 0) {
                    $new_inquiry_count = $record_activity_count->new_inquiry_count;
                    $record_activity_count->new_inquiry_count = $new_inquiry_count + 1;
                    $record_activity_count->save();

                } else {
                    $record_activity_count = new RecordInquiry();
                    $record_activity_count->date = strtotime('today');
                    $record_activity_count->new_inquiry_count = 1;
                    $record_activity_count->quotation_count = 0;
                    $record_activity_count->followup_count = 0;
                    $record_activity_count->booking_count = 0;
                    $record_activity_count->cancellation_count = 0;
                    $record_activity_count->save();
                }

                Yii::$app->getSession()->setFlash('success', 'Inquiry is added successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'age_model' => $age_model,
                    'agent_model' => $agent_model,
                    'city' => $city,
                    'room_arr' => $room_arr,
                    'child_age' => $child_age,
                    'city_name' => $city_name,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'age_model' => $age_model,
                'agent_model' => $agent_model,
                'city' => $city,
                'room_arr' => $room_arr,
                'child_age' => $child_age,
                'city_name' => $city_name,
            ]);
        }
    }


    /**
     * Updates an existing Inquiry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $status = $model->status;

        $model->from_date = date('Y-m-d', $model->from_date);
        $model->return_date = date('Y-m-d', $model->return_date);

        $age_model = new InquiryChildAge();
        $agent_model = new Agent();
        $city = City::getCity();
        $city_name = '';
        if ($model->customer_type == CustomerTypes::AGENT) {
            $city_name = $model->agent->city_id;
        }
        //  echo $city_name;exit;

        $child_age = [];
        $child = InquiryChildAge::find()->where(['inquiry_id' => $id])->all();
        foreach ($child as $val) {

            $child_age[] = $val->age;
        }

        $room_type_id = [];
        $room_arr = [];
        $room_type_id_arr = InquiryRoomType::find()->where(['inquiry_id' => $model->id])->all();

        foreach ($room_type_id_arr as $val) {
            $room_arr[] = $val->roomType->type;
        }
        $room = RoomType::getRoomTypes();

        if ($agent_model->load(Yii::$app->request->post())) {
            if (!in_array($agent_model->city_id, $city, true)) {
                $city_model = new City();
                $city_model->name = $agent_model->city_id;
                if ($city_model->save()) {
                    $agent_model->city_id = $city_model->id;
                }
            } else {
                $city_id = City::find()->where(['name' => $agent_model->city_id])->one();
                $agent_model->city_id = $city_id->id;
                $agent_model->save();
            }
        }

        if ($model->load(Yii::$app->request->post()) && $age_model->load(Yii::$app->request->post())) {
            $room_type = [];
            $room_type = Yii::$app->request->post("room_type");
            $room_num = count(Yii::$app->request->post("room_type"));


            if (isset($room_type)) {
                $room_diff = array_diff($room_type, $room);
                $room_same = array_intersect($room_type, $room);

                if (count($room_diff) > 0) {

                    foreach ($room_diff as $rt) {
                        $room_model = new RoomType();
                        $room_model->type = $rt;
                        $room_model->save();
                        $room_type_id[] = $room_model->id;
                    }
                } else {

                    $room_id = RoomType::find()->where(['IN', 'type', $room_same])->all();

                    foreach ($room_id as $rid) {
                        $room_type_id[] = $rid->id;
                    }

                }
            }

            //$model->mobile = preg_replace('/(\D+)/', '', $model->mobile);
            $model->status = $status;
            $model->from_date = strtotime($model->from_date);
            $model->return_date = strtotime($model->return_date);
            if ($model->customer_type == CustomerTypes::CUSTOMER) {
                $model->agent_id = '';
            }

            if ($model->save()) {
                InquiryChildAge::deleteAll('inquiry_id = :inquiry_id', [':inquiry_id' => $id]);
                InquiryRoomType::deleteAll('inquiry_id = :inquiry_id', [':inquiry_id' => $id]);
                //room type

                for ($i = 0; $i < $room_num; $i++) {
                    $inq_room_type = new InquiryRoomType();
                    $inq_room_type->inquiry_id = $id;
                    $inq_room_type->room_type_id = $room_type_id[$i];
                    $inq_room_type->save();
                }

// children count
                if (isset($age_model->age)) {
                    foreach ($age_model->age as $val) {
                        $child_model = new InquiryChildAge();
                        $child_model->inquiry_id = $model->id;
                        $child_model->age = $val;
                        $child_model->save();
                    }
                }

                $activity = new RecordActivity();
                $activity->inquiry_id = $model->id;
                $activity->activity = ActivityTypes::UPDATED;
                $activity->created_by = Yii::$app->user->identity->id;
                $activity->status = 10;
                $activity->save();

                Yii::$app->getSession()->setFlash('info', 'Inquiry is updated successfully.');

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'age_model' => $age_model,
                    'agent_model' => $agent_model,
                    'city' => $city,
                    'room_arr' => $room_arr,
                    'child_age' => $child_age,
                    'city_name' => $city_name,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'age_model' => $age_model,
                'agent_model' => $agent_model,
                'city' => $city,
                'room_arr' => $room_arr,
                'child_age' => $child_age,
                'city_name' => $city_name,
            ]);
        }
    }

    /**
     *
     * Add quote
     * @param integer $inquiry_id
     * @return mixed
     */

    public function actionAddQuote($inquiry_id)
    {

        $child_age = [];
        $room_type_id_arr = [];
        $room_arr = [];
        $room_type_id = [];
        $old_media = [];

        $followup = Followup::find()->where(['inquiry_id' => $inquiry_id])->orderBy(['id' => SORT_DESC])->all();
        $child = InquiryChildAge::find()->where(['inquiry_id' => $inquiry_id])->all();
        $packagemodel = new Package();
        $country_model = new Country();
        $city_model = new City();
        $price_model = new PackagePricing();
        $searchModel = new PackageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        foreach ($child as $val) {
            $child_age[] = $val->age;
        }

        $model = $this->findModel($inquiry_id);

        $age_model = new InquiryPackageChildAge();

        $model->from_date = date('Y-m-d', $model->from_date);
        $model->return_date = date('Y-m-d', $model->return_date);

        $room_type_id_arr = InquiryRoomType::find()->where(['inquiry_id' => $model->id])->all();

        foreach ($room_type_id_arr as $val) {
            $room_arr[] = $val->roomType->type;
        }

        $room_type_array = RoomType::getRoomTypes();
        $room = array_values($room_type_array);

        $quotation_model = new InquiryPackage();
        $follow_up = new Followup();
        $itinerary = new Itinerary();
        $price_model = new PackagePricing();
        $package_model = new Package();

        if ($model->load(Yii::$app->request->post()) && $quotation_model->load(Yii::$app->request->post())) {
            // echo'<pre>'; print_r(Yii::$app->request->get()); exit;
            // echo '<pre>';print_r($quotation_model);exit;
            ////////////Inquiry-Package

            /*Room type*/
            $room_type = [];


                     $customize_text = Yii::$app->request->post("customize_text");

                     $email_cc = Yii::$app->request->post("email_cc");


            // echo'<pre>'; print_r(Yii::$app->request->post()); exit;
            //echo $age_model->age; exit;

            $room_type = Yii::$app->request->post("room_type");
            $room_num = count(Yii::$app->request->post("room_type"));


            if (isset($room_type)) {
                $room_diff = array_diff($room_type, $room);
                $room_same = array_intersect($room_type, $room);

                if (count($room_diff) > 0) {

                    foreach ($room_diff as $rt) {
                        $room_model = new RoomType();
                        $room_model->type = $rt;
                        $room_model->save();
                        $room_type_id[] = $room_model->id;
                    }
                } else {

                    $room_id = RoomType::find()->where(['IN', 'type', $room_same])->all();

                    foreach ($room_id as $rid) {
                        $room_type_id[] = $rid->id;
                    }

                }
            }
            /*room type*/
           // echo $quotation_model->email_cc; exit;
            $quotation_model->inquiry_id = $inquiry_id;

            if ($quotation_model->is_itinerary == "on") {
                $quotation_model->is_itinerary = InquiryPackage::WITH_ITINERARY;
            } else {
                $quotation_model->is_itinerary = InquiryPackage::WITHOUT_ITINERARY;
            }
            $quotation_model->passenger_name = $model->name;
            $quotation_model->passenger_email = $model->email;
            // $model->mobile = preg_replace('/(\D+)/', '', $model->mobile);
            $quotation_model->passenger_mobile = $model->mobile;
            $quotation_model->destination = $model->destination;
            $quotation_model->leaving_from = $model->leaving_from;
            $quotation_model->adult_count = $model->adult_count;
            $quotation_model->children_count = $model->children_count;
            $quotation_model->room_count = $model->room_count;
            $quotation_model->from_date = strtotime($model->from_date);
            $quotation_model->return_date = strtotime($model->return_date);
            $quotation_model->no_of_nights = $model->no_of_days;
            $quotation_model->notes = $model->notes;
            $quotation_model->email_cc = $email_cc;
            $quotation_model->inquiry_details = $model->inquiry_details;
            /*
            $new_inquiry_model = $this->findModel($inquiry_id);
            $new_inquiry_model->inquiry_head = $model->inquiry_head;
            $new_inquiry_model->quotation_manager = $model->quotation_manager;
            $new_inquiry_model->quotation_staff = $model->quotation_staff;
            $new_inquiry_model->follow_up_head = $model->follow_up_head;
            $new_inquiry_model->follow_up_staff = $model->follow_up_staff;
            $new_inquiry_model->save();

            */
            if ($model->type != InquiryTypes::PER_ROOM_PER_NIGHT) {
                $package_model = Yii::$app->request->post('Package');
                $quotation_model->package_name = $package_model['name'];
                $quotation_model->no_of_days_nights = $package_model['no_of_days_nights'];
                $quotation_model->itinerary_name = $package_model['itinerary_name'];
                $quotation_model->package_include = $package_model['package_include'];
                $quotation_model->pricing_details = $package_model['pricing_details'];
                $quotation_model->package_exclude = $package_model['package_exclude'];
                $quotation_model->terms_and_conditions = $package_model['terms_and_conditions'];
                $quotation_model->other_info = $package_model['other_info'];


            } else {
                $quotation_model->package_id = null;
            }
            if ($quotation_model->save()) {
                $id = $quotation_model->package_id;
                $quotation_id = $quotation_model->id;

                if ($model->type != InquiryTypes::PER_ROOM_PER_NIGHT) {

                    //quotation city/country
                    $country = Yii::$app->request->post('package_country');
                    for ($i = 0; $i < count($country); $i++) {
                        $inquiry_package_country = new InquiryPackageCountry();
                        $inquiry_package_country->inquiry_package_id = $id;
                        $inquiry_package_country->country_id = $country[$i];
                        $inquiry_package_country->save();
                    }

                    $cities = Yii::$app->request->post('package_city');
                    $nights = Yii::$app->request->post('no_of_nights');
                    $city = City::getCity();
                    for ($j = 0; $j < count($cities); $j++) {
                        if ($cities[$j] != '') {
                            $inquiry_package_city = new InquiryPackageCity();
                            $inquiry_package_city->inquiry_package_id = $id;
                            $inquiry_package_city->no_of_nights = $nights[$j];
                            if (!in_array($cities[$j], $city, true)) {
                                $city_model = new City();
                                $city_model->name = $cities[$j];
                                if ($city_model->save()) {
                                    $inquiry_package_city->city_id = $city_model->id;
                                }
                            } else {
                                $ct = City::find()->where(['name' => $cities[$j]])->all();
                                $inquiry_package_city->city_id = $ct[0]->id;
                            }
                            $inquiry_package_city->save();
                        }
                    }
                }
                /*Room type*/
                for ($i = 0; $i < $room_num; $i++) {
                    $inq_room_type = new InquiryPackageRoomType();
                    $inq_room_type->inquiry_package_id = $quotation_id;
                    $inq_room_type->room_type_id = $room_type_id[$i];
                    $inq_room_type->save();
                }
                /*--*/

                if ($age_model->load(Yii::$app->request->post())) {
                    // echo'<pre>'; print_r($age_model); exit;
                    if (isset($age_model->age)) {
                        // children count
                        foreach ($age_model->age as $val) {
                            $child_model = new InquiryPackageChildAge();
                            $child_model->inquiry_package_id = $quotation_id;
                            $child_model->age = $val;
                            $child_model->save();
                        }
                    }
                }
                //

/////////////// Follow-up
                $follow_up->date = strtotime('today') + 86400;
                $follow_up->by = $model->follow_up_head;
                $follow_up->inquiry_package_id = $quotation_id;
                $follow_up->inquiry_id = $inquiry_id;
                $follow_up->note = "First Follow Up";
                $follow_up->status = 10;
                $follow_up->is_followup = 0;
                $follow_up->save();


                if ($model->type != InquiryTypes::PER_ROOM_PER_NIGHT) {
                    ////////////// Quotation-Itinerary
                    if ($quotation_model->is_itinerary = InquiryPackage::WITH_ITINERARY) {
                        $i_model = Itinerary::find()->where(['package_id' => $quotation_model->package_id])->all();

                        foreach ($i_model as $i) {
                            $old_media[] = $i->media_id;
                        }
                        $itinerary = Yii::$app->request->post('Itinerary');
                        $itinerary_model = new Itinerary();
                        $title = $itinerary['title'];
                        $description = $itinerary['description'];
                        $banner_id = [];

                        /*$package_itinerary = Itinerary::find()->where(['package_id' => $quotation_model->package_id])->all();
                        foreach ($package_itinerary as $p) {
                            $banner_id[] = $p->media_id;
                        }*/

                        $banner_id = [];
                        $media_id = '';
                        $no_of_nights = $quotation_model->no_of_days_nights;
                        for ($i = 0; $i <= $no_of_nights; $i++) {
                            $media[$i] = UploadedFile::getInstance($itinerary_model, "media_id[$i]");
                            if ($media[$i] != null && !$media[$i]->getHasError()) {
                                $media_id = LogoUploader::LogoUpload($media[$i], MediaTypes::PACKAGE_ITINERARY_PHOTO, null, $id);
                            }
                            if (isset($media[$i])) {
                                $banner_id[] = $media_id;
                            } else {
                                if (isset($old_media[$i]))
                                    $banner_id[] = $old_media[$i];
                                else
                                    $banner_id[] = null;
                            }
                        }
                        $count = count($title);
                        for ($i = 0; $i < $count; $i++) {
                            if ($title[$i] != '' || $description[$i] != '') {
                                $i_model = new QuotationItinerary();
                                $i_model->quotation_id = $quotation_id;
                                $i_model->title = $title[$i];
                                $i_model->description = $description[$i];
                                if (isset($banner_id[$i]))
                                    $i_model->media_id = $banner_id[$i];
                                //echo '<pre>';print_r($i_model);exit;
                                $i_model->save();
                            }
                        }
                    }

                    //////////// Quotation-Price
                    $price_model = Yii::$app->request->post('PackagePricing');
                    $price_types = PriceType::getPriceTypesNames();
                    $currency_id = $price_model['currency_id'];
                    $type = $price_model['type'];
                    $price = $price_model['price'];
                    $c = count($price);
                    for ($i = 0; $i < $c; $i++) {
                        if (!in_array($type[$i], $price_types, true)) {
                            $type_model = new PriceType();
                            $type_model->type = $type[$i];
                            if ($type_model->save()) {
                                $type[$i] = $type_model->id;
                            }
                        } else {
                            $types = PriceType::find()->where(['type' => $type[$i]])->all();
                            $type[$i] = $types[0]->id;
                        }
                    }

                    for ($i = 0; $i < $c; $i++) {
                        $p_model = new QuotationPricing();
                        $p_model->quotation_id = $quotation_id;
                        $p_model->currency_id = $currency_id[$i];
                        $p_model->type = $type[$i];
                        $p_model->price = $price[$i];

                        $p_model->save();
                    }
                }

//////////////// change status of inquiry to quoted
                $inquiry_model = $this->findModel($inquiry_id);
                $inquiry_model->status = InquiryStatusTypes::QUOTED;
                $inquiry_model->save();

/////////////// record acivity
                $record_activity = new RecordActivity();
                $record_activity->inquiry_id = $inquiry_id;
                $record_activity->activity = ActivityTypes::QUOTED;
                $record_activity->created_by = Yii::$app->user->identity->id;
                $record_activity->status = 10;
                $record_activity->save();

                /* $activity = new RecordActivity();
                 $activity->inquiry_id = $inquiry_id;
                 $activity->activity = ActivityTypes::FOLLOWED_UP;
                 $activity->created_by = $follow_up->by;
                 $activity->status = 10;
                 $activity->save();*/


                $this->sendMail($quotation_model, InquiryStatusTypes::QUOTED,$customize_text);

                //add record to report

                $inquiry_activity = new InquiryActivity();
                $inquiry_activity->inquiry_id = $inquiry_id;
                $inquiry_activity->user_id = Yii::$app->user->identity->id;
                $inquiry_activity->date = strtotime('today');
                $inquiry_activity->type = InquiryActivityTypes::QUOTED;
                if ($inquiry_activity->save()) {
                    $activity_count = ActivityCount::find()->where(['user_id' => $inquiry_activity->user_id, 'date' => $inquiry_activity->date])->one();
                    if (count($activity_count) > 0) {
                        $quotation_count = $activity_count->quotation_count;
                        $activity_count->quotation_count = $quotation_count + 1;
                        $activity_count->save();

                    } else {
                        $activity_count = new ActivityCount();
                        $activity_count->user_id = Yii::$app->user->identity->id;
                        $activity_count->date = strtotime('today');
                        $activity_count->quotation_count = 1;
                        $activity_count->followup_count = 0;
                        $activity_count->save();
                    }

                    $record_activity_count = RecordInquiry::find()->where(['date' => strtotime('today')])->one();
                    if (count($record_activity_count) > 0) {
                        $quotation_count = $record_activity_count->quotation_count;
                        $record_activity_count->quotation_count = $quotation_count + 1;
                        $record_activity_count->save();

                    } else {
                        $record_activity_count = new RecordInquiry();
                        $record_activity_count->date = strtotime('today');
                        $record_activity_count->new_inquiry_count = 0;
                        $record_activity_count->quotation_count = 1;
                        $record_activity_count->followup_count = 0;
                        $record_activity_count->booking_count = 0;
                        $record_activity_count->cancellation_count = 0;
                        $record_activity_count->save();
                    }
                }

                Yii::$app->getSession()->setFlash('success', 'Quotation is created successfully.');
                return $this->redirect(['quoted-inquiry', 'id' => $inquiry_id]);
            } else {
                //echo '<pre>';print_r($quotation_model->getErrors());exit;
                return $this->render('add_quote', [
                    'model' => $model,
                    'quotation_model' => $quotation_model,
                    'follow_up' => $follow_up,
                    'room_arr' => $room_arr,
                    'age_model' => $age_model,
                    'child_age' => $child_age,
                    'followup' => $followup,
                    // 'packagesearchModel' => $packagesearchModel,
                    'country_model' => $country_model,
                    'city_model' => $city_model,
                    'price_model' => $price_model,
                    'package_model' => $package_model,
                    'packagemodel' => $packagemodel,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }

        } else {
            return $this->render('add_quote', [
                'model' => $model,
                'quotation_model' => $quotation_model,
                'follow_up' => $follow_up,
                'room_arr' => $room_arr,
                'age_model' => $age_model,
                'child_age' => $child_age,
                'followup' => $followup,
                // 'packagesearchModel' => $packagesearchModel,
                'country_model' => $country_model,
                'city_model' => $city_model,
                'price_model' => $price_model,
                'package_model' => $package_model,
                'packagemodel' => $packagemodel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function Export($type = "")
    {
        $f_type = '';
        if (isset(Yii::$app->request->queryParams['InquirySearch']['followup_type']))
            $f_type = Yii::$app->request->queryParams['InquirySearch']['followup_type'];
        $searchModel = new InquirySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $type, $f_type)->query->all();
        $count = count($dataProvider);
        // echo '<pre>'; print_r($dataProvider); exit;
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'INQUIRY ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'PASSENGER NAME');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'PASSENGER EMAIL');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'PASSENGER MOBILE');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'DATE OF TRAVEL');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'DESTINATION');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'LEAVING FROM');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'ROOMS');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'ADULTS');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'CHILDREN');
        $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'STAFF');
        $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'STATUS');

        for ($i = 2; $i < $count + 2; $i++) {
            if ($type == InquiryStatusTypes::QUOTED) {//echo "<pre>";print_r($dataProvider[$i - 2]->inquiry->inquiry_id);exit;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, 'KR-' . $dataProvider[$i - 2]->inquiry->inquiry_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $dataProvider[$i - 2]->inquiry->name);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $dataProvider[$i - 2]->inquiry->email);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $dataProvider[$i - 2]->inquiry->mobile);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $dataProvider[$i - 2]->inquiry->date_with_days);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $dataProvider[$i - 2]->inquiry->destination);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $dataProvider[$i - 2]->inquiry->leaving_from);
                $roomtypes = '';
                if (isset($dataProvider[$i - 2]->inquiry->inquiryRoomTypes)) {
                    foreach ($dataProvider[$i - 2]->inquiry->inquiryRoomTypes as $room) {
                        $roomtypes .= $room->roomType->type;
                        $roomtypes .= ',';
                    }
                    $roomtypes = rtrim($roomtypes, ',');
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, $dataProvider[$i - 2]->inquiry->room_count . "/" . $roomtypes);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $dataProvider[$i - 2]->inquiry->adult_count);
                if ($dataProvider[$i - 2]->inquiry->children_count > 0) {
                    if (isset($dataProvider[$i - 2]->inquiry->inquiryChildAges)) {
                        $ch_ages = '';
                        foreach ($dataProvider[$i - 2]->inquiry->inquiryChildAges as $age) {
                            $ch_ages .= $age->age . ' years,';
                        }
                        $child_ages = rtrim($ch_ages, ",");
                        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $dataProvider[$i - 2]->inquiry->children_count . '/' . $child_ages);
                    }
                } else {
                    $objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, "0");
                };

                if ($dataProvider[$i - 2]->inquiry->status == InquiryStatusTypes::IN_QUOTATION || $dataProvider[$i - 2]->inquiry->status == InquiryStatusTypes::AMENDED) {
                    if ($dataProvider[$i - 2]->inquiry->quotation_staff != "")
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $dataProvider[$i - 2]->inquiry->quotationStaff->username);
                    else
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $dataProvider[$i - 2]->quotationManager->username);
                }
                if ($dataProvider[$i - 2]->status == InquiryStatusTypes::QUOTED) {
                    if ($dataProvider[$i - 2]->inquiry->follow_up_staff != "")
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $dataProvider[$i - 2]->inquiry->followUpStaff->username);
                    else
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $dataProvider[$i - 2]->followUpHead->username);
                }
                if ($dataProvider[$i - 2]->inquiry->status == InquiryStatusTypes::CANCELLED || $dataProvider[$i - 2]->inquiry->status == InquiryStatusTypes::COMPLETED) {
                    if ($dataProvider[$i - 2]->inquiry->inquiry_head != "")
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $dataProvider[$i - 2]->inquiry->inquiryHead->username);
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, InquiryStatusTypes::$index_status[$dataProvider[$i - 2]->inquiry->status]);
            } else {//echo "<pre>";print_r($dataProvider[$i - 2]->inquiry_id);exit;
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, 'KR-' . $dataProvider[$i - 2]->inquiry_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $dataProvider[$i - 2]->name);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $dataProvider[$i - 2]->email);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $dataProvider[$i - 2]->mobile);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $dataProvider[$i - 2]->date_with_days);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $dataProvider[$i - 2]->destination);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $dataProvider[$i - 2]->leaving_from);
                $roomtypes = '';
                if (isset($dataProvider[$i - 2]->inquiryRoomTypes)) {
                    foreach ($dataProvider[$i - 2]->inquiryRoomTypes as $room) {
                        $roomtypes .= $room->roomType->type;
                        $roomtypes .= ',';
                    }
                    $roomtypes = rtrim($roomtypes, ',');
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, $dataProvider[$i - 2]->room_count . "/" . $roomtypes);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $dataProvider[$i - 2]->adult_count);
                if ($dataProvider[$i - 2]->children_count > 0) {
                    if (isset($dataProvider[$i - 2]->inquiryChildAges)) {
                        $ch_ages = '';
                        foreach ($dataProvider[$i - 2]->inquiryChildAges as $age) {
                            $ch_ages .= $age->age . ' years,';
                        }
                        $child_ages = rtrim($ch_ages, ",");
                        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $dataProvider[$i - 2]->children_count . '/' . $child_ages);
                    }
                } else {
                    $objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, "0");
                };
                if ($dataProvider[$i - 2]->status == InquiryStatusTypes::IN_QUOTATION || $dataProvider[$i - 2]->status == InquiryStatusTypes::AMENDED) {
                    if ($dataProvider[$i - 2]->quotation_staff != "")
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $dataProvider[$i - 2]->quotationStaff->username);
                    else
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $dataProvider[$i - 2]->quotationManager->username);
                }
                if ($dataProvider[$i - 2]->status == InquiryStatusTypes::QUOTED) {
                    if ($dataProvider[$i - 2]->follow_up_staff != "")
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $dataProvider[$i - 2]->followUpStaff->username);
                    else
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $dataProvider[$i - 2]->followUpHead->username);
                }
                if ($dataProvider[$i - 2]->status == InquiryStatusTypes::CANCELLED || $dataProvider[$i - 2]->status == InquiryStatusTypes::COMPLETED) {
                    if ($dataProvider[$i - 2]->inquiry_head != "")
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $dataProvider[$i - 2]->inquiryHead->username);
                }
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, InquiryStatusTypes::$index_status[$dataProvider[$i - 2]->status]);
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('Inquiry Report');

// Redirect output to a client’s web browser (Excel5)
        $filename = "Inquiry Report" . date('m-d-Y_his') . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    /**
     *
     * Search Package list to add quotation
     * @return mixed
     *
     */


    public function actionAddQuoteSearch()
    {
        //echo '<pre>';print_r(Yii::$app->request->get());exit;
        $country = Yii::$app->request->get('country');
        $city = Yii::$app->request->get('city');
        $nights = Yii::$app->request->get('nights');
        $name = Yii::$app->request->get('name');
        $for_agent = Yii::$app->request->get('for_agent');
        $search = Yii::$app->request->get('search');
        $city_str = '';
        $country_str = '';
        if (count($country) > 1)
            $country_str = implode(',', $country);
        if (count($city) > 1)
            $city_str = implode(',', $city);

        //select2 ajax call
        /* $clean = ['more' => false];


         $main_query = Package::find()->joinWith(['packageCities','packageCountries','packagePricings'])
             ->select('package.id,package.name')
             ->where('package.status=10')
             ->andFilterWhere(['package.no_of_days_nights' => $nights])
             ->andFilterWhere(['like', 'package.name', $name])
             ->andFilterWhere(['like', 'package.name', $search])
            // ->andFilterWhere(['IN', 'package_city.city_id', $city_str])
             ->andFilterWhere(['package_city.city_id' => $city])
            // ->andFilterWhere(['IN', 'package_country.country_id', $country_str])
             ->andFilterWhere(['package_country.country_id'=> $country])
             ->all();


         $clean['results'] = $main_query;

         echo Json::encode($clean);*/

        $models = Package::find()->joinWith(['packageCities', 'packageCountries', 'packagePricings'])
            ->where('package.status=10')
            ->andFilterWhere(['package.no_of_days_nights' => $nights])
            ->andFilterWhere(['like', 'package.name', $name])
            // ->andFilterWhere(['IN', 'package_city.city_id', $city_str])
            ->andFilterWhere(['package_city.city_id' => $city])
            // ->andFilterWhere(['IN', 'package_country.country_id', $country_str])
            ->andFilterWhere(['package_country.country_id' => $country])
            ->andFilterWhere(['package.for_agent' => $for_agent])
            ->all();


        //echo '<pre>';print_r($models);exit;
        echo $this->renderPartial("_package_list", [
            'models' => $models,

        ]);

    }


    /**
     *
     * Search Package details to add quotation
     * @param integer $package_id
     * @return mixed
     */

    public function actionSearchModel($package_id, $itinerary_flag)
    {
        $package_model = Package::findOne($package_id);
        $price_model = PackagePricing::find()->where(['package_id' => $package_id])->all();
        $itinerary = Itinerary::find()->where(['package_id' => $package_id])->all();

        echo $this->renderPartial("_package_view", [
            'package_model' => $package_model,
            'itinerary' => $itinerary,
            'itinerary_flag' => $itinerary_flag,
            'price_model' => $price_model,

        ]);


    }


    /**
     * Deletes an existing Inquiry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $status = $model->status;
        $model->status = InquiryStatusTypes::CANCELLED;
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
        }
        if ($model->save()) {
            $activity = new RecordActivity();
            $activity->inquiry_id = $model->id;
            $activity->activity = ActivityTypes::CANCELLED;
            $activity->created_by = Yii::$app->user->identity->id;
            $activity->status = 10;
            $activity->save();

            if ($status != InquiryStatusTypes::IN_QUOTATION) {
                //add record to report

                $inquiry_activity = new InquiryActivity();
                $inquiry_activity->inquiry_id = $id;
                $inquiry_activity->user_id = Yii::$app->user->identity->id;
                $inquiry_activity->date = strtotime('today');
                $inquiry_activity->type = InquiryActivityTypes::FOLLOWUP;
                if ($inquiry_activity->save()) {
                    $activity_count = ActivityCount::find()->where(['user_id' => $inquiry_activity->user_id, 'date' => $inquiry_activity->date])->one();
                    if (count($activity_count) > 0) {
                        $followup_count = $activity_count->followup_count;
                        $activity_count->followup_count = $followup_count + 1;
                        $activity_count->save();

                    } else {
                        $activity_count = new ActivityCount();
                        $activity_count->user_id = Yii::$app->user->identity->id;
                        $activity_count->date = strtotime('today');
                        $activity_count->quotation_count = 0;
                        $activity_count->followup_count = 1;
                        $activity_count->save();
                    }

                    $record_activity_count = RecordInquiry::find()->where(['date' => strtotime('today')])->one();
                    if (count($record_activity_count) > 0) {
                        $followup_count = $record_activity_count->followup_count;
                        $cancellation_count = $record_activity_count->cancellation_count;
                        $record_activity_count->followup_count = $followup_count + 1;
                        $record_activity_count->cancellation_count = $cancellation_count + 1;
                        $record_activity_count->save();

                    } else {
                        $record_activity_count = new RecordInquiry();
                        $record_activity_count->date = strtotime('today');
                        $record_activity_count->new_inquiry_count = 0;
                        $record_activity_count->quotation_count = 0;
                        $record_activity_count->followup_count = 1;
                        $record_activity_count->booking_count = 0;
                        $record_activity_count->cancellation_count = 1;
                        $record_activity_count->save();
                    }
                }
            }

            Yii::$app->getSession()->setFlash('error', 'Inquiry is cancelled successfully.');
            return $this->redirect(['index', 'type' => InquiryStatusTypes::CANCELLED]);
        } else {
            return $this->redirect(['index', 'type' => InquiryStatusTypes::CANCELLED]);
        }
    }


    /**
     * Finds the Inquiry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inquiry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Inquiry::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionTimeline($id)
    {
        $model = RecordActivity::find()->where(['inquiry_id' => $id])->all();
        $inquiry = $this->findModel($id);
        return $this->render('timeline', [
            'model' => $model,
            'inquiry' => $inquiry,
        ]);
    }

    public function actionSearchAgent($city = '')
    {
        if ($city == null) {
            $city_agent = Agent::getAgent();
        } else {
            $city_agent = Agent::getAgent($city);
        }
        $agents = json_encode($city_agent);
        echo $agents;
    }

    /*
     * search quotation staff according to head
     */
    public function actionSearchQuotationStaff($head = '')
    {
        $quotation_staff = User::getQuotationStaff($head);
        $staff = json_encode($quotation_staff);
        echo $staff;
    }

    /*
     * search followup staff according to head
     */
    public function actionSearchFollowupStaff($head = '')
    {
        $followup_staff = User::getFollowupStaff($head);
        $staff = json_encode($followup_staff);
        echo $staff;
    }

    private function sendMail($model, $status = '',$customize_text='')
    {
        if ($status == InquiryStatusTypes::AMENDED) {
            $cc = [];

           $cc[] = 'sejalgupta48@gmail.com';
            //$cc[] = 'sejal@fierydevs.com';

            if (isset($model->inquiryHead)) {
                $cc[] = $model->inquiryHead->email;
            }
           /* if (isset($model->inquiry->quotation_manager)) {
                $cc[] = $model->quotationManager->email;
            }*/
            if (isset($model->quotationManager)) {
                $cc[] = $model->quotationManager->email;
            }
            if (isset($model->followUpHead)) {
                $cc[] = $model->followUpHead->email;
            }
            if (isset($model->followUpStaff)) {
                $cc[] = $model->followUpStaff->email;
            }

            if ($model->quotationStaff != '') {
                \Yii::$app->mailer->compose(['html' => 'inquiryAmended-html'], ['model' => $model, 'username' => $model->quotationStaff->username])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($model->quotationStaff->email)
                    ->setCc($cc)
                    ->setSubject('Inquiry Amended/' . 'KR-' . $model->inquiry_id . '/' . $model->name . '/' . $model->mobile . '/' . $model->destination)
                    ->send();
            }else{
                if ($model->quotationManager != '') {
                    \Yii::$app->mailer->compose(['html' => 'inquiryAmended-html'], ['model' => $model, 'username' => $model->quotationManager->username])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                        ->setTo($model->quotationManager->email)
                        ->setSubject('Inquiry Amended/' . 'KR-' . $model->inquiry_id . '/' . $model->name . '/' . $model->mobile . '/' . $model->destination)
                        ->send();
                }
                if ($model->inquiryHead != '') {
                    \Yii::$app->mailer->compose(['html' => 'inquiryAmended-html'], ['model' => $model, 'username' => $model->inquiryHead->username])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                        ->setTo($model->inquiryHead->email)
                        ->setSubject('Inquiry Amended/' . 'KR-' . $model->inquiry_id . '/' . $model->name . '/' . $model->mobile . '/' . $model->destination)
                        ->send();
                }
            }

        }
        else if ($status == InquiryStatusTypes::QUOTED) {

            $cc = [];

           // $logged_user=[];
           // $user_model = User::find()->where(['role'=>UserTypes::ADMIN])->all();
            $cc[] = 'sejalgupta48@gmail.com';
            if(isset($model->email_cc)) {
                if ($model->email_cc != "") {
                    $email = explode(',', $model->email_cc);
                    foreach ($email as $val) {
                        $cc[] = $val;
                    }
                    // print_r($cc);
                    //echo $customize_text;
                }
            }

                if (isset($model->inquiry->inquiryHead)) {
                    $cc[] = $model->inquiry->inquiryHead->email;
            }
            if (isset($model->inquiry->quotationManager)) {
                $cc[] = $model->inquiry->quotationManager->email;
            }
            if (isset($model->inquiry->quotationStaff)) {
                $cc[] = $model->inquiry->quotationStaff->email;
            }
            if (isset($model->inquiry->followUpHead)) {
                $cc[] = $model->inquiry->followUpHead->email;
            }
            if (isset($model->inquiry->followUpStaff)) {
                $cc[] = $model->inquiry->followUpStaff->email;
            }



            //$logged_user[]=Yii::$app->user->identity->email;

            //$cc= array_diff($cc,$logged_user);
            if (isset($model->passenger_email)) {

                \Yii::$app->mailer->compose(['html' => 'quotation-html', 'text' => 'quotation-text'], ['model' => $model, 'signature' => Yii::$app->user->identity->signature,'customize_text'=>$customize_text])
                    ->setFrom(Yii::$app->user->identity->email)
                    //->setTo($model->passenger_email)
                    ->setTo($model->passenger_email)
                    ->setCc($cc)
                    ->setSubject('Inquiry Quotation/' . 'KR-' . $model->inquiry->inquiry_id . '/' . $model->passenger_name . '/' . $model->passenger_mobile . '/'. $model->destination)
                    ->send();
            }

        }
        else {
            $cc = [];
           // $user_model = User::find()->where(['role'=>UserTypes::ADMIN])->all();
            $cc[] = 'sejalgupta48@gmail.com';


            if (isset($model->inquiryHead)) {
                $cc[] = $model->inquiryHead->email;
            }
            if (isset($model->quotationManager)) {
                $cc[] = $model->quotationManager->email;
            }
            if (isset($model->followUpHead)) {
                $cc[] = $model->followUpHead->email;
            }
            if (isset($model->followUpStaff)) {
                $cc[] = $model->followUpStaff->email;
            }
            if ($model->quotationStaff != '') {
                \Yii::$app->mailer->compose(['html' => 'inquiryAdd-html', 'text' => 'inquiryAdd-text'], ['model' => $model, 'username' => $model->quotationStaff->username])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($model->quotationStaff->email)
                    ->setCc($cc)
                    ->setSubject('New Inquiry/' . 'KR-' . $model->inquiry_id . '/' . $model->name . '/' . $model->mobile . '/' . $model->destination)
                    ->send();
            }else{
                if ($model->quotationManager != '') {
                    \Yii::$app->mailer->compose(['html' => 'inquiryAdd-html', 'text' => 'inquiryAdd-text'], ['model' => $model, 'username' => $model->quotationManager->username])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                        ->setTo($model->quotationManager->email)
                        ->setSubject('New Inquiry/' . 'KR-' . $model->inquiry_id . '/' . $model->name . '/' . $model->mobile . '/' . $model->destination)
                        ->send();
                }
                if ($model->inquiryHead != '') {
                    \Yii::$app->mailer->compose(['html' => 'inquiryAdd-html', 'text' => 'inquiryAdd-text'], ['model' => $model, 'username' => $model->inquiryHead->username])
                        ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                        ->setTo($model->inquiryHead->email)
                        ->setSubject('New Inquiry/' . 'KR-' . $model->inquiry_id . '/' .  $model->name . '/' . $model->mobile . '/' . $model->destination)
                        ->send();
                }
            }


        }

    }

    public function actionCancelPendingFollowups() {
        $ids = Yii::$app->request->post('ids');

        $models = Inquiry::findAll($ids);
        foreach ($models as $model) {//echo "<pre>";print_r($model);exit;
            $status = $model->status;
            $model->status = InquiryStatusTypes::CANCELLED;
            if ($model->save()) {
                $activity = new RecordActivity();
                $activity->inquiry_id = $model->id;
                $activity->activity = ActivityTypes::CANCELLED;
                $activity->created_by = Yii::$app->user->identity->id;
                $activity->status = 10;
                $activity->save();

                if ($status != InquiryStatusTypes::IN_QUOTATION) {
                    //add record to report

                    $inquiry_activity = new InquiryActivity();
                    $inquiry_activity->inquiry_id = $model->id;
                    $inquiry_activity->user_id = Yii::$app->user->identity->id;
                    $inquiry_activity->date = strtotime('today');
                    $inquiry_activity->type = InquiryActivityTypes::FOLLOWUP;
                    if ($inquiry_activity->save()) {
                        $activity_count = ActivityCount::find()->where(['user_id' => $inquiry_activity->user_id, 'date' => $inquiry_activity->date])->one();
                        if (count($activity_count) > 0) {
                            $followup_count = $activity_count->followup_count;
                            $activity_count->followup_count = $followup_count + 1;
                            $activity_count->save();

                        } else {
                            $activity_count = new ActivityCount();
                            $activity_count->user_id = Yii::$app->user->identity->id;
                            $activity_count->date = strtotime('today');
                            $activity_count->quotation_count = 0;
                            $activity_count->followup_count = 1;
                            $activity_count->save();
                        }

                        $record_activity_count = RecordInquiry::find()->where(['date' => strtotime('today')])->one();
                        if (count($record_activity_count) > 0) {
                            $followup_count = $record_activity_count->followup_count;
                            $cancellation_count = $record_activity_count->cancellation_count;
                            $record_activity_count->followup_count = $followup_count + 1;
                            $record_activity_count->cancellation_count = $cancellation_count + 1;
                            $record_activity_count->save();

                        } else {
                            $record_activity_count = new RecordInquiry();
                            $record_activity_count->date = strtotime('today');
                            $record_activity_count->new_inquiry_count = 0;
                            $record_activity_count->quotation_count = 0;
                            $record_activity_count->followup_count = 1;
                            $record_activity_count->booking_count = 0;
                            $record_activity_count->cancellation_count = 1;
                            $record_activity_count->save();
                        }
                    }
                }
            }
        }
        return json_encode(['success' => true, 'type' => InquiryStatusTypes::CANCELLED]);
    }
}
