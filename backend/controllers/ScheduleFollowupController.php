<?php

namespace backend\controllers;

use backend\models\enums\UserTypes;
use common\filters\IpFilter;
use Yii;
use common\models\ScheduleFollowup;
use common\models\ScheduleFollowupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScheduleFollowupController implements the CRUD actions for ScheduleFollowup model.
 */
class ScheduleFollowupController extends Controller
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
     * Lists all ScheduleFollowup models.
     * @return mixed
     */
    public function actionIndex($inquiry_id='')
    {
        $searchModel = new ScheduleFollowupSearch();
        if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$inquiry_id,UserTypes::ADMIN);
        }
       /* else if(Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF)
        {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$inquiry_id,UserTypes::QUOTATION_STAFF);
        }*/
        else
        {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $inquiry_id);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScheduleFollowup model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {

            $scheduled_at = Yii::$app->request->post('schedule_date'). Yii::$app->request->post('schedule_time');
            $scheduled_at = strtotime($scheduled_at);
            $model->scheduled_at = $scheduled_at;
            $model->scheduled_by = Yii::$app->user->identity->id;
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Followup Mail updated successfully.');
                return $this->redirect(['view', 'id' => $id]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'Followup Mail not updated.');
                return $this->redirect(['view', 'id' => $id]);
            }
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing ScheduleFollowup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->status==10)
        {
            $model->status = 0;
            if($model->save())
            {
                Yii::$app->getSession()->setFlash('error', 'Mail is deleted successfully.');
                return $this->redirect(['index']);
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the ScheduleFollowup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScheduleFollowup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScheduleFollowup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
