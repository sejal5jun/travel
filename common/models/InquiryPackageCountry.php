<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "inquiry_package_country".
 *
 * @property integer $id
 * @property integer $inquiry_package_id
 * @property integer $country_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Country $country
 * @property Package $inquiryPackage
 */
class InquiryPackageCountry extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inquiry_package_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['inquiry_package_id', 'country_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
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
            'country_id' => 'Country ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackage()
    {
        return $this->hasOne(Package::className(), ['id' => 'inquiry_package_id']);
    }
}
