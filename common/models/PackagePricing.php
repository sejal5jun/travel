<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "package_pricing".
 *
 * @property integer $id
 * @property integer $package_id
 * @property integer $currency_id
 * @property integer $type
 * @property string $price
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Currency $currency
 * @property Package $package
 * @property PriceType $type0
 */
class PackagePricing extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'package_pricing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['package_id', 'currency_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'safe'],
            //[['price'], 'required'],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['package_id'], 'exist', 'skipOnError' => true, 'targetClass' => Package::className(), 'targetAttribute' => ['package_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'package_id' => 'Package',
            'currency_id' => 'Currency',
            'type' => 'Type',
            'price' => 'Price',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['id' => 'package_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(PriceType::className(), ['id' => 'type']);
    }
}
