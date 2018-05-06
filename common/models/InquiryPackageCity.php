<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "inquiry_package_city".
 *
 * @property integer $id
 * @property integer $inquiry_package_id
 * @property integer $city_id
 * @property integer $no_of_nights
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property City $city
 * @property Package $inquiryPackage
 */
class InquiryPackageCity extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inquiry_package_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['inquiry_package_id', 'city_id', 'no_of_nights', 'created_at', 'updated_at'], 'integer'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['inquiry_package_id'], 'exist', 'skipOnError' => true, 'targetClass' => Package::className(), 'targetAttribute' => ['inquiry_package_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inquiry_package_id' => 'Inquiry Package ID',
            'city_id' => 'City ID',
            'no_of_nights' => 'No Of Nights',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackage()
    {
        return $this->hasOne(Package::className(), ['id' => 'inquiry_package_id']);
    }
}
