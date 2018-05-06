<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property PackageCountry[] $packageCountries
 * @property InquiryPackageCountry[] $inquiryPackageCountries
 */
class Country extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'status' => 'Status',
            'created_at' => 'Added On',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageCountries()
    {
        return $this->hasMany(PackageCountry::className(), ['country_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackageCountries()
    {
        return $this->hasMany(InquiryPackageCountry::className(), ['country_id' => 'id']);
    }

    /**
     * get all countries(id=>name)
     */
    public static function getCountries()
    {
        $data =  static::find()->where(['status' => 10])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','name'); //id = your ID model, name = your caption
        return $value;
    }
}
