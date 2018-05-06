<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "inquiry_package_child_age".
 *
 * @property integer $id
 * @property integer $inquiry_package_id
 * @property string $age
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property InquiryPackage $inquiryPackage
 */
class InquiryPackageChildAge extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inquiry_package_child_age';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['inquiry_package_id', 'created_at', 'updated_at'], 'integer'],
            [['age'], 'number'],
            [['age'], 'required'],
            [['inquiry_package_id'], 'exist', 'skipOnError' => true, 'targetClass' => InquiryPackage::className(), 'targetAttribute' => ['inquiry_package_id' => 'id']],
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
            'age' => 'Age',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackage()
    {
        return $this->hasOne(InquiryPackage::className(), ['id' => 'inquiry_package_id']);
    }
}
