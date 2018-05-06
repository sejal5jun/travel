<?php

namespace backend\controllers;

use backend\models\enums\ActivityTypes;
use backend\models\enums\InquiryStatusTypes;
use common\filters\IpFilter;
use common\models\Booking;
use common\models\Inquiry;
use common\models\InquiryPackage;
use common\models\RecordActivity;
use Yii;
use common\models\Followup;
use common\models\FollowupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FollowupController implements the CRUD actions for Followup model.
 */
class FollowupController extends Controller
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
     * Lists all Followup models.
     * @param $status
     * @return mixed
     */
    public function actionIndex($status='')
    {
        if($status=='')
            $status=Followup::PENDING_FOLLOWUPS;
        $date = strtotime('today') + 86400;
        $today = strtotime('today');

        $followups = Followup::find()->where(['status' => Followup::PENDING_FOLLOWUPS, 'is_followup' => 0])->andWhere(['<', 'date', $date])->all();
        foreach($followups as $f){
            $f->status = Followup::OVERDUE_FOLLOWUPS;
            $f->save();
        }
        $todays_follow = Followup::find()->where(['date' => $today])->all();
        foreach($todays_follow as $f){
            $f->status = Followup::PENDING_FOLLOWUPS;
            $f->save();
        }
        $searchModel = new FollowupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$status);
        $model = Followup::find()->where(['status' => 10])->all();

        $inquiry_new_model= new Inquiry();
        $followup_model= new Followup();
        $booking_model= new Booking();

        if ($inquiry_new_model->load(Yii::$app->request->post())) {

            //echo'<pre>'; print_r($model); exit;
            $inquiry_model = Inquiry::find()->where(['id' => Yii::$app->request->post('inquiry_id')])->one();
            $inquiry_model->status = $inquiry_new_model->status;
            $inquiry_model->notes = $inquiry_new_model->notes;

            if($inquiry_model->save()){
                $followup = Followup::find()->where(['inquiry_id' => $inquiry_model->id])->all();
                foreach($followup as $f){
                    $f->is_followup=1;
                    $f->save();
                }

                //followup details

                $in_pack = InquiryPackage::find()->where(['inquiry_id' => $inquiry_model->id])->orderBy(['id' => 'desc'])->all();

                if($inquiry_new_model->status== InquiryStatusTypes::COMPLETED)
                {
                    $booking = Booking::find()->orderBy(['id' => SORT_ASC])->all();
                    $count = count($booking);
                    if ($count > 0)
                        $last_id = $booking[$count - 1]->booking_id;
                    else
                        $last_id = 0;

                    $booking_model->booking_id = "KRB-".($last_id[4] + 1);
                    $booking_model->inquiry_id=$inquiry_model->id;
                    $booking_model->inquiry_package_id=$in_pack[0]->id;;
                    $booking_model->save();
                }


                if ($followup_model->load(Yii::$app->request->post())) {

                    if($followup_model->date!="") {
                        //echo'<pre>'; print_r($in_pack); exit;
                        $followup_model->date = strtotime($followup_model->date);
                        $followup_model->inquiry_package_id = $in_pack[0]->id;
                        $followup_model->by = $inquiry_model->follow_up_head;
                        $followup_model->inquiry_id = $inquiry_model->id;
                        $followup_model->note=$inquiry_new_model->notes;

                        //print_r($followup_model); exit;
                        $followup_model->save();
                    }
                }
                //record inquiry
                $state = '';
                if($inquiry_model->status == InquiryStatusTypes::QUOTED)
                    $state = ActivityTypes::FOLLOWED_UP;
                if($inquiry_model->status == InquiryStatusTypes::AMENDED)
                    $state = ActivityTypes::AMENDED;
                if($inquiry_model->status == InquiryStatusTypes::COMPLETED)
                    $state = ActivityTypes::COMPLETED;
                if($inquiry_model->status == InquiryStatusTypes::CANCELLED)
                    $state = ActivityTypes::CANCELLED;

                if($state==ActivityTypes::FOLLOWED_UP){
                    $followup_activity = new RecordActivity();
                    $followup_activity->inquiry_id = $inquiry_model->id;
                    $followup_activity->activity = ActivityTypes::FOLLOWED_UP;
                    $followup_activity->notes =$inquiry_new_model->notes;
                    $followup_activity->created_by = $inquiry_model->created_by;
                    $followup_activity->status = 10;
                    $followup_activity->save();
                }else{
                    $followup_activity = new RecordActivity();
                    $followup_activity->inquiry_id = $inquiry_model->id;
                    $followup_activity->activity = ActivityTypes::FOLLOWED_UP;
                    $followup_activity->notes =$inquiry_new_model->notes;
                    $followup_activity->created_by = $inquiry_model->created_by;
                    $followup_activity->status = 10;
                    $followup_activity->save();

                    $activity = new RecordActivity();
                    $activity->inquiry_id = $inquiry_model->id;
                    $activity->activity = $state;
                    $activity->notes =$inquiry_new_model->notes;
                    $activity->created_by = $inquiry_model->created_by;
                    $activity->status = 10;
                    $activity->save();
                }

                //send mail if inquiry is amended
                if($inquiry_new_model->status==InquiryStatusTypes::AMENDED)
                    $this->sendMail($model,$inquiry_new_model->status);

                $type = $inquiry_new_model->status;
                if($inquiry_new_model->status==InquiryStatusTypes::AMENDED)
                    $type = InquiryStatusTypes::IN_QUOTATION;
                Yii::$app->getSession()->setFlash('success', 'Inquiry status changed successfully.');
                return $this->redirect(['index', 'type' => $type]);
            }
            else
            {
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'status' => $status,
                    'followup_model'=>$followup_model,
                    'booking_model'=>$booking_model
                ]);

            }

        }

        else {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
                'status' => $status,
                'followup_model'=>$followup_model,
                'booking_model'=>$booking_model,
                'inquiry_new_model'=>$inquiry_new_model
            ]);
        }

    }

    /**
     * Displays a single Followup model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Followup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Followup();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Followup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Followup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Followup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Followup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Followup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionList(){
		$this->render("list");
	}

    private function sendMail($model,$status='')
    {

            if ($model->inquiry->quotation_manager != '') {
                \Yii::$app->mailer->compose(['html' => 'inquiryAmended-html', 'text' => 'inquiryAmended-text'], ['model' => $model,'username'=>$model->inquiry->quotationManager->username])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($model->inquiry->quotationManager->email)
                    ->setSubject('Inquiry is Amended: ' . 'KR-' . $model->inquiry_id)
                    ->send();
            }
            if ($model->inquiry->follow_up_head != '') {
                \Yii::$app->mailer->compose(['html' => 'inquiryAmended-html', 'text' => 'inquiryAmended-text'], ['model' => $model,'username'=>$model->inquiry->quotationManager->username])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($model->inquiry->followUpHead->email)
                    ->setSubject('Inquiry is Amended: ' . 'KR-' . $model->inquiry_id)
                    ->send();
            }
            if ($model->inquiry->follow_up_staff != '') {
                \Yii::$app->mailer->compose(['html' => 'inquiryAmended-html', 'text' => 'inquiryAmended-text'], ['model' => $model,'username'=>$model->inquiry->quotationManager->username])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($model->inquiry->followUpStaff->email)
                    ->setSubject('Inquiry is Amended: ' . 'KR-' . $model->inquiry_id)
                    ->send();
            }
            if ($model->inquiry->quotation_staff != '') {
                \Yii::$app->mailer->compose(['html' => 'inquiryAmended-html', 'text' => 'inquiryAmended-text'], ['model' => $model,'username'=>$model->inquiry->quotationManager->username])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($model->inquiry->quotationStaff->email)
                    ->setSubject('Inquiry is Amended: ' . 'KR-' . $model->inquiry_id)
                    ->send();
            }
            if ($model->inquiry->inquiry_head != '') {
                \Yii::$app->mailer->compose(['html' => 'inquiryAmended-html', 'text' => 'inquiryAmended-text'], ['model' => $model,'username'=>$model->inquiry->quotationManager->username])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($model->inquiry->inquiryHead->email)
                    ->setSubject('Inquiry is Amended: ' . 'KR-' . $model->inquiry_id)
                    ->send();
            }



    }
}
