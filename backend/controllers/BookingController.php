<?php

namespace backend\controllers;

use common\filters\IpFilter;
use Yii;
use common\models\Booking;
use common\models\BookingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BookingController implements the CRUD actions for Booking model.
 */
class BookingController extends Controller
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
                        'allow' => true,
                        'roles' => ['@'],
                    ],


                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Booking models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model= Booking::find()->all();

        // echo'<pre>'; print_r($events); exit;

        $searchModel = new BookingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model
        ]);
    }
    public function actionGetCalenderDate()
    {
        $date_int=[];
        $date=[];
        $title=[];
        $date_count=[];
        $events=[];
        $url=[];
        $model= Booking::find()->all();


        foreach($model as $values)
        {
            $date_int[]=$values->inquiryPackage->from_date;
            $title[]=$values->booking_id;
            $date[]=date("Y-m-d",$values->inquiryPackage->from_date);
            $url[]=Yii::$app->urlManager->createAbsoluteUrl(['booking/view','id'=> $values->id ]);
        }

                 foreach($model as $k=>$value)
                {
                    $events[$k]['title']=$title[$k];
                    $events[$k]['url']=$url[$k];
                    $events[$k]['start']=$date[$k];
                    $events[$k]['className']='event-success';
                }


        return json_encode($events);
    }

    /**
     * Displays a single Booking model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)

    {
        $model=Booking::find()->where(['id'=>$id])->one();
        $roomtypes = '';

       // echo'<pre>'; print_r($model); exit;
        if (isset($model->inquiryPackage->inquiryPackageRoomTypes)) {
            foreach ($model->inquiryPackage->inquiryPackageRoomTypes as $room) {
                $roomtypes .= $room->roomType->type;
                $roomtypes .= ',';
            }
            $roomtypes = rtrim($roomtypes, ',');
        }
          // print_r($model->inquiryPackage->inquiryPackageRoomTypes[0]->roomType->type); exit;
        return $this->render('view', [
            'model' => $model,
            'roomtypes'=>$roomtypes,
        ]);
    }

    /**
     * Creates a new Booking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Booking();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Booking model.
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
     * Deletes an existing Booking model.
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
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Booking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Booking::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
