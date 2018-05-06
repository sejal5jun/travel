<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "currency".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property PackagePricing[] $packagePricings
 * @property QuotationPricing[] $quotationPricings
 * @property Booking[] $bookings
 */
class Currency extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
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
            [['name'], 'string', 'max' => 45],
            [['name'], 'required'],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagePricings()
    {
        return $this->hasMany(PackagePricing::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotationPricings()
    {
        return $this->hasMany(QuotationPricing::className(), ['currency_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['currency_id' => 'id']);
    }

    /**
     * get all active currencies
     */
    public static function getCurrency()
    {
        $data =  static::find()->where(['status' => 10])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','name'); //id = your ID model, name = your caption
        return $value;
    }
}
