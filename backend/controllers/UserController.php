<?php

namespace backend\controllers;

use backend\components\LogoUploader;
use backend\models\enums\DirectoryTypes;
use backend\models\enums\MediaTypes;
use backend\models\enums\UserTypes;
use common\filters\IpFilter;
use common\models\Media;
use PHPExcel;
use PHPExcel_IOFactory;
use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
           /* 'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'update', 'view', 'delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['edit-profile', 'change-password'],
                        'allow' => true,
                        'roles' => ['admin', 'quotation_manager', 'follow_up_manager'],
                    ],
                ],
            ],*/
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $export = Yii::$app->request->get('export');
        $search = Yii::$app->request->get('search');
        if(isset($export) && !isset($search)) {
            $this->Export();
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $old_media = $model->media_id;
        $role = $model->role;
        //$model->scenario = 'update';
        if($model->load(Yii::$app->request->post())){

            if($model->confirm_password!=''){

                $model->setPassword($model->confirm_password);
               // $model->generateAuthKey();
            }
            if($model->role==UserTypes::ADMIN || $model->role==UserTypes::QUOTATION_MANAGER || $model->role==UserTypes::FOLLOW_UP_MANAGER)
                $model->head = null;
            if($model->role==UserTypes::QUOTATION_STAFF)
                $model->head = Yii::$app->request->post('quotation_head');
            if($model->role==UserTypes::FOLLOW_UP_STAFF)
                $model->head = Yii::$app->request->post('followup_head');
            $media = UploadedFile::getInstance($model, 'media_id');
            if ($media != null && !$media->getHasError()) {
                $media_id = LogoUploader::LogoUpload($media,MediaTypes::PROFILE_PHOTO, $type = $model->role);
            }
            if (isset($media_id)) {
                $model->media_id = $media_id;
            }
            else{
                $model->media_id = $old_media;
                if($old_media!='' && $role!=$model->role){
                    $media = Media::find()->where(['id' => $old_media])->one();
                    $fname = $media->file_name;
                    if(file_exists($role==UserTypes::ADMIN ? DirectoryTypes::getAdminDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                        $role==UserTypes::QUOTATION_MANAGER ? DirectoryTypes::getQuotationManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                                DirectoryTypes::getFollowUpManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname)){
                        rename($role==UserTypes::ADMIN ? DirectoryTypes::getAdminDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                            $role==UserTypes::QUOTATION_MANAGER ? DirectoryTypes::getQuotationManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                                    DirectoryTypes::getFollowUpManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname, $model->role==UserTypes::ADMIN ? DirectoryTypes::getAdminDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                            $model->role==UserTypes::QUOTATION_MANAGER ? DirectoryTypes::getQuotationManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                                    DirectoryTypes::getFollowUpManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname);
                    }
                }
            }
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('info', 'Profile is updated successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('view', [
                    'model' => $model,
                ]);
            }
        }else {

            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';
		if($model->load(Yii::$app->request->post())){
           // echo '<pre>';print_r(Yii::$app->request->post());exit;
			$model->setPassword($model->confirm_password);
			$model->generateAuthKey();

            $media = UploadedFile::getInstance($model, 'media_id');
            if ($media != null && !$media->getHasError()) {
                $media_id = LogoUploader::LogoUpload($media, MediaTypes::PROFILE_PHOTO, $type = $model->role);
            }
            if (isset($media_id)) {
                $model->media_id = $media_id;
            }
            if($model->role==UserTypes::ADMIN || $model->role==UserTypes::QUOTATION_MANAGER ||  $model->role==UserTypes::FOLLOW_UP_MANAGER)
                $model->head = null;
            if($model->role==UserTypes::QUOTATION_STAFF)
                $model->head = Yii::$app->request->post('quotation_head');
            if($model->role==UserTypes::FOLLOW_UP_STAFF)
                $model->head = Yii::$app->request->post('followup_head');
			if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'User is added successfully.');
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
               //echo '<pre>';print_r($model->getErrors());exit;
				return $this->render('create', [
					'model' => $model,
				]);
			}
		}else{
			return $this->render('create', [
				'model' => $model,
			]);
		}
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_media = $model->media_id;
        $role = $model->role;
       // $model->scenario = 'update';
		if($model->load(Yii::$app->request->post())){
            if($model->role==UserTypes::ADMIN || $model->role==UserTypes::QUOTATION_MANAGER ||  $model->role==UserTypes::FOLLOW_UP_MANAGER)
                $model->head = null;
            if($model->role==UserTypes::QUOTATION_STAFF)
                $model->head = Yii::$app->request->post('quotation_head');
            if($model->role==UserTypes::FOLLOW_UP_STAFF)
                $model->head = Yii::$app->request->post('followup_head');
            if($model->role==UserTypes::BOOKING_STAFF)
                $model->head = Yii::$app->request->post('head');

            if($model->confirm_password!=''){

                $model->setPassword($model->confirm_password);
                //$model->generateAuthKey();
            }
            $media = UploadedFile::getInstance($model, 'media_id');
            if ($media != null && !$media->getHasError()) {
                $media_id = LogoUploader::LogoUpload($media, MediaTypes::PROFILE_PHOTO, $type = $model->role);
            }
            if (isset($media_id)) {
                $model->media_id = $media_id;
            }
            else{
                $model->media_id = $old_media;
                if($old_media!='' && $role!=$model->role){
                    $media = Media::find()->where(['id' => $old_media])->one();
                    $fname = $media->file_name;
                    if(file_exists($role==UserTypes::ADMIN ? DirectoryTypes::getAdminDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                        $role==UserTypes::QUOTATION_MANAGER ? DirectoryTypes::getQuotationManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                        $role==UserTypes::FOLLOW_UP_MANAGER ? DirectoryTypes::getFollowUpManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                        DirectoryTypes::getStaffDirectory(DirectoryTypes::UPLOADS, false) . $fname )){
                        rename($role==UserTypes::ADMIN ? DirectoryTypes::getAdminDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                            $role==UserTypes::QUOTATION_MANAGER ? DirectoryTypes::getQuotationManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                            $role==UserTypes::FOLLOW_UP_MANAGER ? DirectoryTypes::getFollowUpManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                            DirectoryTypes::getStaffDirectory(DirectoryTypes::UPLOADS, false) . $fname,
                            $model->role==UserTypes::ADMIN ? DirectoryTypes::getAdminDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                            $model->role==UserTypes::QUOTATION_MANAGER ? DirectoryTypes::getQuotationManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                            $model->role==UserTypes::FOLLOW_UP_MANAGER ? DirectoryTypes::getFollowUpManagerDirectory(DirectoryTypes::UPLOADS, false) . $fname :
                            DirectoryTypes::getInquiryCollectorDirectory(DirectoryTypes::UPLOADS, false) . $fname);
                    }
                }
            }
			if ($model->save()) {
                Yii::$app->getSession()->setFlash('info', 'Profile is updated successfully.');
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				return $this->render('update', [
					'model' => $model,
				]);
			}
		}else{
			return $this->render('update', [
				'model' => $model,
			]);
		}
    }

    /**
     *
     * Edit Profile
     *
     */
    public function actionEditProfile()
    {
        $id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);
        $old_media = $model->media_id;
        if ($model->load(Yii::$app->request->post())) {
            if($model->role==UserTypes::ADMIN || $model->role==UserTypes::QUOTATION_MANAGER  || $model->role==UserTypes::FOLLOW_UP_MANAGER)
                $model->head = null;
            $media = UploadedFile::getInstance($model, 'media_id');
            if ($media != null && !$media->getHasError()) {
                $media_id = LogoUploader::LogoUpload($media, MediaTypes::PROFILE_PHOTO, $type = $model->role);
            }
            if (isset($media_id)) {
                $model->media_id = $media_id;
            }
            else{
                $model->media_id = $old_media;
            }
            if($model->save()) {
                Yii::$app->getSession()->setFlash('info', 'Profile is updated successfully.');
                return $this->redirect(Yii::$app->request->referrer);
            }
            else{
                return $this->render('edit_profile', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('edit_profile', [
                'model' => $model,
            ]);
        }
    }

    /**
     *
     * Change Password
     *
     */

    public function actionChangePassword()
    {
        $id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);
        $model->scenario = 'changepass';

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->new_password);
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Password is changed successfully.');
                return $this->redirect(Yii::$app->homeUrl);
            } else {
                return $this->render('change_password', [
                    'model' => $model,
                ]);
            }

        } else {
            return $this->render('change_password', [
                'model' => $model,
            ]);
        }


    }

    /**
     * Deletes an existing User model.
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
                Yii::$app->getSession()->setFlash('error', 'Profile is deactivated successfully.');
                return $this->redirect(['index']);
            }
        }
        else{
            $model->status = 10;
            if($model->save())
            {
                Yii::$app->getSession()->setFlash('success', 'Profile is activated successfully.');
                return $this->redirect(['index']);
            }
        }
            return $this->redirect(['index']);

    }

    /**
     * Export to Excel
     *
     */
    public function Export()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams)->query->all();
        $count = count($dataProvider);
        // echo '<pre>'; print_r($dataProvider); exit;
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'USERNAME');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'EMAIL');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'EMAIL');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'ROLE');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'STATUS');

        for ($i = 2; $i < $count + 2; $i++) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $dataProvider[$i - 2]->username);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $dataProvider[$i - 2]->email);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $dataProvider[$i - 2]->mobile);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, UserTypes::$headers[$dataProvider[$i - 2]->role]);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $dataProvider[$i - 2]->status==User::STATUS_ACTIVE ? "Active" : "Inactive");

        }
//echo 3;exit;
        $objPHPExcel->getActiveSheet()->setTitle('User Report');

// Redirect output to a client’s web browser (Excel5)
        $filename = "User Report" . date('m-d-Y_his') . ".xls";
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
//echo 4 ; exit;
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
