<?php

namespace backend\controllers;

use common\filters\IpFilter;
use common\models\City;
use Yii;
use common\models\Agent;
use common\models\AgentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AgentController implements the CRUD actions for Agent model.
 */
class AgentController extends Controller
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
     * Lists all Agent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Agent model.
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
     * Creates a new Agent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $city_name='';
        $model = new Agent();
        $city = City::getCity();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            if (!in_array($model->city_id, $city, true)) {
                $city_model = new City();
                $city_model->name = $model->city_id;
                if ($city_model->save()) {
                    $model->city_id = $city_model->id;
                }
            } else {
                $city_id = City::find()->where(['name' => $model->city_id])->one();
                $model->city_id = $city_id->id;
            }
            if($model->save()) {
                $agents = Agent::find()->all();
                $allAgents = "";
                foreach ($agents as $agent) {
                    $allAgents .= "<option value='" . $agent->id . "'>" . $agent->name . "</option>";
                }
                return json_encode([
                    'message' => 'Agent is added successfully.',
                    'error' => false,
                    'model' => $model,
                    'allAgents' => $allAgents
                ]);
            } else {
                return json_encode([
                    'message' => 'Agent is not added.',
                    'error' => true,
                    'errors' => $model->getErrors()
                ]);
            }
        } else if ($model->load(Yii::$app->request->post())) {
            if (!in_array($model->city_id, $city, true)) {
                $city_model = new City();
                $city_model->name = $model->city_id;
                if ($city_model->save()) {
                    $model->city_id = $city_model->id;
                }
            } else {
                $city_id = City::find()->where(['name' => $model->city_id])->one();
                $model->city_id = $city_id->id;
            }
            if($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Agent is added successfully.');
                return $this->redirect(['index']);
            }
            else{
                return $this->render('create', [
                    'model' => $model,
                    'city_name'=>$city_name
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
                'city_name'=>$city_name
            ]);
        }
    }

    /**
     * Updates an existing Agent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $city_name='';
        $model = $this->findModel($id);
        $city = City::getCity();

        if (isset($model->city_id)) {

            $city_model = City::findOne($model->city_id);
            $city_name = $city_model->name;
        }

        if ($model->load(Yii::$app->request->post())) {
            if (!in_array($model->city_id, $city, true)) {
                $city_model = new City();
                $city_model->name = $model->city_id;
                if ($city_model->save()) {
                    $model->city_id = $city_model->id;
                }
            } else {
                $city_id = City::find()->where(['name' => $model->city_id])->one();
                $model->city_id = $city_id->id;
            }
            if($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Agent is updated successfully.');
                return $this->redirect(['index']);
            }
            else{
                return $this->render('update', [
                    'model' => $model,
                    'city_name'=>$city_name
                ]);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
                'city_name'=>$city_name
            ]);
        }
    }

    /**
     * Deletes an existing Agent model.
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
                Yii::$app->getSession()->setFlash('error', 'Agent is deactivated successfully.');
                return $this->redirect(['index']);
            }
        }
        else{
            $model->status = 10;
            if($model->save())
            {
                Yii::$app->getSession()->setFlash('success', 'Agent is activated successfully.');
                return $this->redirect(['index']);
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Agent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Agent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
