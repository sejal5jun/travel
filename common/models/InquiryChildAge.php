<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "inquiry_child_age".
 *
 * @property integer $id
 * @property integer $inquiry_id
 * @property string $age
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Inquiry $inquiry
 */
class InquiryChildAge extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inquiry_child_age';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['inquiry_id', 'created_at', 'updated_at'], 'integer'],
            [['age'], 'number'],
            [['age'], 'required'],
            [['inquiry_id'], 'exist', 'skipOnError' => true, 'targetClass' => Inquiry::className(), 'targetAttribute' => ['inquiry_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inquiry_id' => 'Inquiry ID',
            'age' => 'Age',
            'status' => 'Status',
            'created_at' => 'Added On',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiry()
    {
        return $this->hasOne(Inquiry::className(), ['id' => 'inquiry_id']);
    }
}
