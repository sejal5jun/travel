<?php

namespace backend\controllers;

use backend\components\LogoUploader;
use backend\models\enums\DirectoryTypes;
use backend\models\enums\MediaTypes;
use backend\models\enums\PackageTypes;
use backend\models\enums\PricingTypes;
use common\filters\IpFilter;
use common\models\City;
use common\models\Itinerary;
use common\models\ItinerarySearch;
use common\models\Media;
use common\models\PackageBanner;
use common\models\PackageCity;
use common\models\PackageCountry;
use common\models\PackagePricing;
use common\models\PackagePricingSearch;
use common\models\PriceType;
use Yii;
use common\models\Package;
use common\models\PackageSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

/**
 * PackageController implements the CRUD actions for Package model.
 */
class PackageController extends Controller
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
     * Lists all Package models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PackageSearch();
       $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //$dataProvider->pagination->pageSize = 24;
        $model = Package::find()->where(['status' => 10])->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Package model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $itinerary = Itinerary::find()->where(['package_id' => $id])->all();
        $new_itinerary =  new Itinerary;
        $price_model = PackagePricing::find()->where(['package_id' => $id])->all();
        $new_price_model = new PackagePricing;
        $city = City::getCity();
        $price_types = PriceType::getPriceTypesNames();
        $old_media = [];
        foreach($itinerary as $i){
            $old_media[] = $i->media_id;
        }
      //  print_r($old_media); exit;

        if ($model->load(Yii::$app->request->post())) {
            //print_r($model->validity); exit;
            //echo Yii::$app->request->post('for_agent');
            //echo Yii::$app->request->post('for_customer'); exit;
            if(Yii::$app->request->post('for_agent') == '')
            {
                $model->for_agent = 0;
            }else
            {
                $model->for_agent = 1;
            }
            $itinerary_model = new Itinerary();
            $price_model = Yii::$app->request->post('PackagePricing');

            if($model->save())
            {
                $package_id = $id;

                ////country-city
                PackageCountry::deleteAll('package_id = :package_id', [':package_id' => $package_id]);
                PackageCity::deleteAll('package_id = :package_id', [':package_id' => $package_id]);
                $country = Yii::$app->request->post('package_country');
                for($i=0;$i<count($country);$i++){
                    $package_country = new PackageCountry();
                    $package_country->package_id = $package_id;
                    $package_country->country_id = $country[$i];
                    $package_country->save();
                }

                $cities = Yii::$app->request->post('package_city');
                $nights = Yii::$app->request->post('no_of_nights');

                for($j=0;$j<count($cities);$j++){
                    if($cities[$j]!=''){
                        $package_city = new PackageCity();
                        $package_city->package_id = $package_id;
                        $package_city->no_of_nights = $nights[$j];
                        if (!in_array($cities[$j], $city, true)) {
                            $city_model = new City();
                            $city_model->name = $cities[$j];
                            if ($city_model->save()) {
                                $package_city->city_id = $city_model->id;
                            }
                        } else {
                            $ct = City::find()->where(['name' => $cities[$j]])->all();
                            $package_city->city_id = $ct[0]->id;
                        }
                        $package_city->save();
                    }
                }
                //if($model->type == PackageTypes::PACKAGE_WITH_ITINERARY) {

                    //////////// Itinerary
                    $itinerary = Yii::$app->request->post('Itinerary');
                    if(isset($itinerary)){
                    $title = $itinerary['title'];
                    $description = $itinerary['description'];
                    $banner_id=[];
                    $media_id='';
                    $no_of_nights = $model->no_of_days_nights;

                    for($i=0;$i<=$no_of_nights;$i++){
                        $media[$i] = UploadedFile::getInstance($itinerary_model, "media_id[$i]");
                       // echo $media[$i]; exit;
                        if ($media[$i] != null && !$media[$i]->getHasError()) {
                            $media_id = LogoUploader::LogoUpload($media[$i], MediaTypes::PACKAGE_ITINERARY_PHOTO, null, $id);
                        }
                        if (isset($media[$i])) {
                            $banner_id[] = $media_id;
                        }
                        else{
                            if(isset($old_media[$i]))
                                $banner_id[] = $old_media[$i];
                            else
                                $banner_id[] = null;
                        }
                    }

                    //print_r($banner_id);exit;

                    Itinerary::deleteAll('package_id = :package_id', [':package_id' => $id]);
                    PackagePricing::deleteAll('package_id = :package_id', [':package_id' => $id]);
                    $count = count($title);
                    for($i=0;$i<$count;$i++){
                        if($title[$i]!='' || $description[$i]!=''){
                            $i_model = new Itinerary();
                            $i_model->package_id = $package_id;
                            //$i_model->name = $itinerary['name'];
                           // $i_model->no_of_itineraries = $itinerary['no_of_itineraries'];
                            $i_model->title = $title[$i];
                            $i_model->description = $description[$i];
                            if(isset($banner_id[$i]))
                                $i_model->media_id = $banner_id[$i];

                            $i_model->save();
                        }
                    }
                }

//////////// Price
                $currency_id = $price_model['currency_id'];
                $type = $price_model['type'];
                $price = $price_model['price'];
                $c = count($price);
                for($i=0;$i<$c;$i++) {
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
                for($i=0;$i<$c;$i++) {
                    $p_model = new PackagePricing();
                    $p_model->package_id = $package_id;
                    $p_model->currency_id = $currency_id[$i];
                    $p_model->type = $type[$i];
                    $p_model->price = $price[$i];

                    $p_model->save();
                }

                Yii::$app->getSession()->setFlash('info', 'Package is updated successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else{
                return $this->render('view', [
                    'model' => $model,
                    'price_model' => $price_model,
                    'itinerary' => $itinerary,
                    'new_itinerary'=>$new_itinerary,
                    'new_price_model'=>$new_price_model,

                ]);

            }
        } else {
            return $this->render('view', [
                'model' => $model,
                'price_model' => $price_model,
                'itinerary' => $itinerary,
                'new_itinerary'=>$new_itinerary,
                'new_price_model'=>$new_price_model,

            ]);
        }
    }

    /**
     * Creates a new Package model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @var $type
     */
    public function actionCreate()
    {
        $model = new Package();
        $itinerary_model = new Itinerary();
        $price_model = new PackagePricing();
        $city = City::getCity();
        $price_types = PriceType::getPriceTypesNames();

        if ($model->load(Yii::$app->request->post()) && $price_model->load(Yii::$app->request->post())) {

            //print_r($model->validity);exit;
            //print_r(Yii::$app->request->post('for_agent'));exit;
             if(Yii::$app->request->post('for_agent') == '')
             {
               $model->for_agent = 0;
             }else
             {
                 $model->for_agent = 1;
             }

            $itinerary = Yii::$app->request->post('Itinerary');
            if($model->save())
            {
                $id = $model->id;

                //add country and city with no of nights

                $country = Yii::$app->request->post('package_country');
                for($i=0;$i<count($country);$i++){
                    $package_country = new PackageCountry();
                    $package_country->package_id = $id;
                    $package_country->country_id = $country[$i];
                    $package_country->save();
                }

                $cities = Yii::$app->request->post('package_city');
                $nights = Yii::$app->request->post('no_of_nights');

                for($j=0;$j<count($cities);$j++){
                    if($cities[$j]!=''){
                        $package_city = new PackageCity();
                        $package_city->package_id = $id;
                        $package_city->no_of_nights = $nights[$j];
                        if (!in_array($cities[$j], $city, true)) {
                            $city_model = new City();
                            $city_model->name = $cities[$j];
                            if ($city_model->save()) {
                                $package_city->city_id = $city_model->id;
                            }
                        } else {
                            $ct = City::find()->where(['name' => $cities[$j]])->all();
                            $package_city->city_id = $ct[0]->id;
                        }
                        $package_city->save();
                    }
                }

                if($itinerary['description'][0]!='') {

                    $itinerary_media= new Itinerary();
                   // print_r($itinerary); exit;
                    //////////// Itinerary
                    $title = $itinerary['title'];
                    $description = $itinerary['description'];
                    $banner_id = [];
                    $media = UploadedFile::getInstances($itinerary_media, 'media_id');

                    foreach ($media as $file) {

                        if ($file != null && !$file->getHasError()) {
                            $media_id = LogoUploader::LogoUpload($file, MediaTypes::PACKAGE_ITINERARY_PHOTO, null, $id);

                        }
                        if (isset($media_id)) {
                            $banner_id[] = $media_id;
                        }
                    }
                    $count = count($title);
                    for ($i = 0; $i < $count; $i++) {
                        if ($description[$i] != '') {
                            $i_model = new Itinerary();
                            $i_model->package_id = $id;
                            //$i_model->name = $itinerary['name'];
                           // $i_model->no_of_itineraries = $itinerary['no_of_itineraries'];
                            $i_model->title = $title[$i];
                            $i_model->description = $description[$i];
                            if (isset($banner_id[$i]))
                                $i_model->media_id = $banner_id[$i];

                            $i_model->save();
                        }
                    }
                }
    //////////// Price

                $price = $price_model->price;
                $type = $price_model->type;
                $currency_id = $price_model->currency_id;
                $c = count($price);
                for($i=0;$i<$c;$i++) {
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

                for($i=0;$i<$c;$i++) {
                    $p_model = new PackagePricing();
                    $p_model->package_id = $id;
                    $p_model->currency_id = $currency_id[$i];
                    $p_model->type = $type[$i];
                    $p_model->price = $price[$i];

                    $p_model->save();
                }

                Yii::$app->getSession()->setFlash('success', 'Package is created successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else{
                return $this->render('create', [
                    'model' => $model,
                    'price_model' => $price_model,
                    'itinerary_model' => $itinerary_model,
                ]);

            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'price_model' => $price_model,
                //'itinerary' => $itinerary,
                'itinerary_model' => $itinerary_model,
            ]);
        }
    }

    /**
     * Updates an existing Package model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $itinerary = Itinerary::find()->where(['package_id' => $id])->all();
        $new_itinerary =  new Itinerary;
        $price_model = PackagePricing::find()->where(['package_id' => $id])->all();
        $new_price_model = new PackagePricing;

        $old_media = [];
        foreach($itinerary as $i){
            $old_media[] = $i->media_id;
        }


        if ($model->load(Yii::$app->request->post())) {
            $itinerary_model = new Itinerary();
            $price_model = Yii::$app->request->post('PackagePricing');

            if($model->save())
            {
                $package_id = $id;
                if($model->type == PackageTypes::PACKAGE_WITH_ITINERARY) {

                    //////////// Itinerary
                    $itinerary = Yii::$app->request->post('Itinerary');
                    $title = $itinerary['title'];
                    $description = $itinerary['description'];
                    $banner_id=[];
                    $media_id='';
                    $no_of_nights = $itinerary['no_of_itineraries'];
                    for($i=0;$i<=$no_of_nights;$i++){
                        $media[$i] = UploadedFile::getInstance($itinerary_model, "media_id[$i]");
                        if ($media[$i] != null && !$media[$i]->getHasError()) {
                            $media_id = LogoUploader::LogoUpload($media[$i], MediaTypes::PACKAGE_ITINERARY_PHOTO, null, $id);
                        }
                        if (isset($media[$i])) {
                            $banner_id[] = $media_id;
                        }
                        else{
                            if(isset($old_media[$i]))
                                $banner_id[] = $old_media[$i];
                            else
                                $banner_id[] = null;
                        }
                    }
                    //print_r($banner_id);exit;

                    Itinerary::deleteAll('package_id = :package_id', [':package_id' => $id]);
                    PackagePricing::deleteAll('package_id = :package_id', [':package_id' => $id]);
                    $count = count($title);
                    for($i=0;$i<$count;$i++){
                        if($title[$i]!='' || $description[$i]!=''){
                            $i_model = new Itinerary();
                            $i_model->package_id = $package_id;
                            $i_model->name = $itinerary['name'];
                            $i_model->no_of_itineraries = $itinerary['no_of_itineraries'];
                            $i_model->title = $title[$i];
                            $i_model->description = $description[$i];
                            if(isset($banner_id[$i]))
                                $i_model->media_id = $banner_id[$i];

                            $i_model->save();
                        }
                    }
                }

//////////// Price
                $currency_id = $price_model['currency_id'];
                $type = $price_model['type'];
                $price = $price_model['price'];

                $c = count($price);
                for($i=0;$i<$c;$i++) {
                    $p_model = new PackagePricing();
                    $p_model->package_id = $package_id;
                    $p_model->currency_id = $currency_id[$i];
                    $p_model->type = $type[$i];
                    $p_model->price = $price[$i];

                    $p_model->save();
                }

                Yii::$app->getSession()->setFlash('info', 'Package is updated successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else{
                return $this->render('update', [
                    'model' => $model,
                    'price_model' => $price_model,
                    'itinerary' => $itinerary,
                    'new_itinerary'=>$new_itinerary,
                    'new_price_model'=>$new_price_model,
                ]);

            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'price_model' => $price_model,
                'itinerary' => $itinerary,
                'new_itinerary'=>$new_itinerary,
                'new_price_model'=>$new_price_model,
            ]);
        }
    }

    /**
     * Deletes an existing Package model.
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
                Yii::$app->getSession()->setFlash('error', 'Package is deleted successfully.');
                return $this->redirect(['index']);
            }
        }
        else{
            $model->status = 10;
            if($model->save())
            {
                Yii::$app->getSession()->setFlash('success', 'Package is activated successfully.');
                return $this->redirect(['index']);
            }
        }

            return $this->redirect(['index']);



    }

    public function actionExportInPdf($id) {
        $model = $this->findModel($id);
        $pdf = Yii::$app->pdf;
        $pdf->cssFile = "@backend/web/css/package-view-pdf.css";
        $pdf->destination = Pdf::DEST_DOWNLOAD;
        $pdf->filename = isset($model->name) ? trim($model->name) . ".pdf" : "Package Name.pdf";
        $pdf->content = Yii::$app->controller->renderPartial('_view_package', [
            'model' => $model,
        ]);
        return $pdf->render();
    }

    /**
     * Finds the Package model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Package the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Package::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
