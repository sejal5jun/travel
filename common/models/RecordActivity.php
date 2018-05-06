<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "record_activity".
 *
 * @property integer $id
 * @property integer $inquiry_id
 * @property integer $activity
 * @property string $notes
 * @property integer $status
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $createdBy
 * @property Inquiry $inquiry
 */
class RecordActivity extends AppActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'record_activity';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inquiry_id', 'activity', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['notes'], 'string'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'activity' => 'Activity',
            'notes' => 'Notes',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Added On',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiry()
    {
        return $this->hasOne(Inquiry::className(), ['id' => 'inquiry_id']);
    }
}
