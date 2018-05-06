<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "followup".
 *
 * @property integer $id
 * @property integer $inquiry_id
 * @property integer $inquiry_package_id
 * @property integer $date
 * @property string $note
 * @property integer $by
 * @property integer $status
 * @property integer $is_followup
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property InquiryPackage $inquiryPackage
 * @property Inquiry $inquiry
 * @property User $by0
 */
class Followup extends AppActiveRecord
{
    const OVERDUE_FOLLOWUPS = 0;
    const PENDING_FOLLOWUPS = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'followup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::PENDING_FOLLOWUPS],
            ['status', 'in', 'range' => [self::PENDING_FOLLOWUPS, self::OVERDUE_FOLLOWUPS]],
            ['is_followup', 'default', 'value' => 0],
            ['is_followup', 'in', 'range' => [0, 1]],
            [['inquiry_package_id', 'inquiry_id', 'by', 'created_at', 'updated_at'], 'integer'],
            [['note'], 'string'],
            [['date'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inquiry_id' => ' Inquiry ID',
            'inquiry_package_id' => 'Inquiry Package ID',
            'date' => 'Follow Up Date',
            'note' => 'Followup Note',
            'by' => 'By',
            'status' => 'Status',
            'is_followup' => 'Is Followup',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackage()
    {
        return $this->hasOne(InquiryPackage::className(), ['id' => 'inquiry_package_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBy0()
{
    return $this->hasOne(User::className(), ['id' => 'by']);
}

    public static function getMultipleInquiry($id)
    {
        $data=  static::find()->where([ 'status' => 10,'inquiry_id'=>$id])->orderby(['date'=>SORT_DESC])->all();

        return $data;
    }


}
