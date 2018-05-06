<?php

namespace common\models;

use backend\components\DirectoryCreator;
use backend\models\enums\PackageTypes;
use Yii;

/**
 * This is the model class for table "package".
 *
 * @property integer $id
 * @property string $name
 * @property int $type
 * @property string $package_include
 * @property string $package_exclude
 * @property string $terms_and_conditions
 * @property string $other_info
 * @property string $pricing_details
 * @property string $no_of_days_nights
 * @property string $validity
 * @property string $till_validity
 * @property integer $for_agent
 * @property string $itinerary_name
 * @property integer $category
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property InquiryPackage[] $inquiryPackages
 * @property Itinerary[] $itineraries
 * @property PackagePricing[] $packagePricings
 * @property PackageCountry[] $packageCountries
 * @property PackageCity[] $packageCities
 * @property InquiryPackageCity[] $inquiryPackageCities
 * @property InquiryPackageCountry[] $inquiryPackageCountries
 */
class Package extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $text;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'package';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['package_include','validity','till_validity', 'package_exclude', 'pricing_details','terms_and_conditions', 'other_info', 'no_of_days_nights'], 'string'],
            [['category', 'created_at','for_agent', 'updated_at', 'type'], 'integer'],
            [['name', 'itinerary_name'], 'string', 'max' => 255],
            [['name'], 'required'],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Package Name',
            'itinerary_name' => 'Itinerary Name',
            'type' => 'Package Type',
            'package_include' => 'Package Includes',
            'package_exclude' => 'Package Excludes',
            'category' => 'Category',
            'no_of_days_nights' => 'No Of Nights/Days',
            'terms_and_conditions' => 'Terms And Conditions',
            'other_info' => 'Other Info',
            'status' => 'Status',
            'created_at' => 'Added On',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackages()
    {
        return $this->hasMany(InquiryPackage::className(), ['package_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItineraries()
    {
        return $this->hasMany(Itinerary::className(), ['package_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagePricings()
    {
        return $this->hasMany(PackagePricing::className(), ['package_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageCountries()
    {
        return $this->hasMany(PackageCountry::className(), ['package_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageCities()
    {
        return $this->hasMany(PackageCity::className(), ['package_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackageCities()
    {
        return $this->hasMany(InquiryPackageCity::className(), ['inquiry_package_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackageCountries()
    {
        return $this->hasMany(InquiryPackageCountry::className(), ['inquiry_package_id' => 'id']);
    }

    /**
     * get all active packages with itinerary
     */
    public static function getItineraryPackage($package_id="")
    {
        if($package_id != null)
            $data =  static::find()->where(['status' => 10])->andWhere(['IN','id',$package_id])->all();
        else
            $data =  static::find()->where(['status' => 10])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','name'); //id = your ID model, name = your caption
        return $value;
    }

    public function fields()
    {
        return [
            // field name is the same as the attribute name
            'id',
            'name',
            'type',
            'category',
            'package_include',
            'package_exclude',
            'terms_and_conditions',
            'other_info',


            'created_at',
            'updated_at',
            'status',
            // field name is "name", its value is defined by a PHP callback


            'text' => function ($model) {
                return $model->name;
            },

        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->text = $this->name;
    }

    /**
     * get all active packages without itinerary
     */
    public static function getPackage()
    {
        $data =  static::find()->where(['type'=> PackageTypes::PACKAGE_WITHOUT_ITINERARY,'status' => 10])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','name'); //id = your ID model, name = your caption
        return $value;
    }

    /**
     * Create directories for package
     */
    public function afterSave($insert, $changedAttributes){
        DirectoryCreator::createPackageDirectory($this->id);
        parent::afterSave($insert, $changedAttributes);
    }
}
