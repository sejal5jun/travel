<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quotation_pricing".
 *
 * @property integer $id
 * @property integer $quotation_id
 * @property integer $currency_id
 * @property integer $type
 * @property string $price
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Currency $currency
 * @property InquiryPackage $quotation
 * @property PriceType $type0
 */
class QuotationPricing extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quotation_pricing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['quotation_id', 'currency_id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'string', 'max' => 45],
            [['quotation_id'], 'required'],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['quotation_id'], 'exist', 'skipOnError' => true, 'targetClass' => InquiryPackage::className(), 'targetAttribute' => ['quotation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quotation_id' => 'Quotation ID',
            'currency_id' => 'Currency ID',
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
    public function getQuotation()
    {
        return $this->hasOne(InquiryPackage::className(), ['id' => 'quotation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(PriceType::className(), ['id' => 'type']);
    }
}
