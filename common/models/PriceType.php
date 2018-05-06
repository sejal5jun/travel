<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "price_type".
 *
 * @property integer $id
 * @property string $type
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property PackagePricing[] $packagePricings
 * @property QuotationPricing[] $quotationPricings
 */
class PriceType extends AppActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price_type';
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
            [['type'], 'string', 'max' => 255],
            [['type'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
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
        return $this->hasMany(PackagePricing::className(), ['type' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotationPricings()
    {
        return $this->hasMany(QuotationPricing::className(), ['type' => 'id']);
    }
    /**
     * get all types(name=>name)
     */

    public static function getPriceTypesNames()
    {
        $data=  static::find()->where([ 'status' => 10])->all();
        $value=(count($data)==0)? []: \yii\helpers\ArrayHelper::map($data,'type','type');
        return $value;
    }
    /**
     * get all types(id=>name)
     */

    public static function getPriceTypes()
    {
        $data=  static::find()->where([ 'status' => 10])->all();
        $value=(count($data)==0)? []: \yii\helpers\ArrayHelper::map($data,'id','type');
        return $value;
    }
}
