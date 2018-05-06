<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_count".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $date
 * @property integer $quotation_count
 * @property integer $followup_count
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class ActivityCount extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const INITIAL_COUNT = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_count';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['quotation_count', 'default', 'value' => self::INITIAL_COUNT],
            ['followup_count', 'default', 'value' => self::INITIAL_COUNT],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['user_id', 'date', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'date' => 'Date',
            'quotation_count' => 'Quotation Count',
            'followup_count' => 'Followup Count',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
