<?php
namespace common\models;

use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\UserTypes;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $mobile
 * @property string $auth_key
 * @property integer $status
 * @property integer $role
 * @property integer $head
 * @property integer $media_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $signature
 *
 * @property Followup[] $followups
 *  @property Booking[] $bookings
 * @property Inquiry[] $inquiries
 * @property Inquiry[] $inquiries0
 * @property Inquiry[] $inquiries1
 * @property Inquiry[] $inquiries2
 * @property Inquiry[] $inquiries3
 * @property Inquiry[] $inquiries4
 * @property RecordActivity[] $recordActivities
 * @property ScheduleFollowup[] $scheduleFollowups
 * @property Media $media
 * @property User[] $users
 * @property User $head0
 * @property InquiryActivity[] $inquiryActivities
 * @property ActivityCount[] $activityCounts
 * @property RecordLogin[] $recordLogins
 * @property RecordBooking[] $recordBookings
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

	public static $statusHeading = [
		self::STATUS_ACTIVE => 'Active',
		self::STATUS_DELETED	=> 'Inactive',
	];

    public $old_password;
    public $new_password;
    public $confirm_password;

    public $quotation_manager_count;
    public $followup_head_count;
    public $head_count;
    public $quotation_staff_count;
    public $followup_staff_count;

    public $quotation_manager_c;
    public $head_c;
    public $quotation_staff_c;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
			[['username', 'email', 'role'], 'required'],
			[['email'], 'email'],
			[['head'], 'integer'],
			[['username', 'email'], 'unique'],
            [['mobile'], 'string', 'max' => 15],
            [['old_password', 'new_password', 'confirm_password', 'signature'], 'safe'],
            [['old_password','new_password','confirm_password'], 'required','on' => 'changepass'],
            ['confirm_password', 'compare', 'compareAttribute'=>'new_password', 'message'=>"Passwords don't match"],
           // ['confirm_password', 'compare', 'compareAttribute'=>'password_hash', 'message'=>"Passwords do not match", 'on' => 'update'],
           // ['confirm_password', 'compare', 'compareAttribute'=>'password_hash', 'message'=>"Passwords do not match", 'on' => 'create'],
            ['old_password', 'findPassword','on' => 'changepass'],
            [['new_password', 'confirm_password'],'required', 'on' => 'create'],
        ];
    }

    public function findPassword($attribute, $params)
    {

        if(!$this->validatePass($this->old_password)){
            $this->addError($attribute,'Old password is incorrect');
            return false;
        }
        return true;


    }
	
    public function validatePass($password)
    {
        $user = User::findOne(Yii::$app->user->identity->id);
        return Yii::$app->security->validatePassword($password, $user->password_hash);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHead0()
    {
        return $this->hasOne(User::className(), ['id' => 'head']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowups()
    {
        return $this->hasMany(Followup::className(), ['by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiries()
    {
        return $this->hasMany(Inquiry::className(), ['quotation_manager' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiries0()
    {
        return $this->hasMany(Inquiry::className(), ['head' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiries1()
    {
        return $this->hasMany(Inquiry::className(), ['quotation_manager' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiries2()
    {
        return $this->hasMany(Inquiry::className(), ['inquiry_head' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiries3()
    {
        return $this->hasMany(Inquiry::className(), ['quotation_staff' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiries4()
    {
        return $this->hasMany(Inquiry::className(), ['quotation_manager' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecordActivities()
    {
        return $this->hasMany(RecordActivity::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScheduleFollowups()
    {
        return $this->hasMany(ScheduleFollowup::className(), ['scheduled_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['head' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryActivities()
    {
        return $this->hasMany(InquiryActivity::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityCounts()
    {
        return $this->hasMany(ActivityCount::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['booking_staff' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecordLogins()
    {
        return $this->hasMany(RecordLogin::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecordBookings()
    {
        return $this->hasMany(RecordBooking::className(), ['user_id' => 'id']);
    }



    public function fields()
    {
        return [
            // field name is the same as the attribute name
            'id',
            'name',
            'email',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'role',
            'head',
            'media_id',
            'created_at',
            'updated_at',
            'status',
            // field name is "name", its value is defined by a PHP callback


            'quotation_manager_count' => function ($model) {
                $type=[InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED,InquiryStatusTypes::AMENDED];
                $inquiry = Inquiry::find()->where(['quotation_manager' => $model->id])->andWhere(['IN', 'status', $type])->all();
                return $model->username . ' (' . count($inquiry) . ')';
            },

            'followup_head_count' => function ($model) {
                $type=[InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED,InquiryStatusTypes::AMENDED];
                $inquiry = Inquiry::find()->where(['follow_up_head' => $model->id])->andWhere(['IN', 'status', $type])->all();
                return $model->username . ' (' . count($inquiry) . ')';
            },

            'head_count' => function ($model) {
                $type=[InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED,InquiryStatusTypes::AMENDED];
                //$inquiry = Inquiry::find()->where(['inquiry_head' => $model->id])->andWhere(['IN', 'status', $type])->orWhere(['follow_up_head' => $model->id])->andWhere(['IN', 'status', $type])->all();
                $inquiry = Inquiry::find()->where(['IN', 'status', $type])->andWhere(['or',['quotation_manager' => $model->id],['follow_up_staff' => $model->id],['follow_up_head' => $model->id],['inquiry_head' => $model->id],['quotation_staff' => $model->id]])->all();
                return $model->username . ' (' . count($inquiry) . ')';
            },

            'quotation_staff_count' => function ($model) {
                $type=[InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED,InquiryStatusTypes::AMENDED];
                $inquiry = Inquiry::find()->where(['quotation_staff' => $model->id])->andWhere(['IN', 'status', $type])->orWhere(['follow_up_staff' => $model->id])->andWhere(['IN', 'status', $type])->all();
                return $model->username . ' (' . count($inquiry) . ')';
            },

            'followup_staff_count' => function ($model) {
                $type=[InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED,InquiryStatusTypes::AMENDED];
                $inquiry = Inquiry::find()->where(['follow_up_staff' => $model->id])->andWhere(['IN', 'status', $type])->orWhere(['follow_up_staff' => $model->id])->andWhere(['IN', 'status', $type])->all();
                return $model->username . ' (' . count($inquiry) . ')';
            },

            'quotation_manager_c' => function ($model) {
                $type=[InquiryStatusTypes::IN_QUOTATION,InquiryStatusTypes::AMENDED];
                $inquiry = Inquiry::find()->where(['quotation_manager' => $model->id])->andWhere(['IN', 'status', $type])->all();
                return count($inquiry);
            },

            'head_c' => function ($model) {
                $type=[InquiryStatusTypes::IN_QUOTATION,InquiryStatusTypes::AMENDED];
                $inquiry = Inquiry::find()->where(['inquiry_head' => $model->id])->andWhere(['IN', 'status', $type])->all();
                return count($inquiry);
            },

            'quotation_staff_c' => function ($model) {
                $type=[InquiryStatusTypes::IN_QUOTATION,InquiryStatusTypes::AMENDED];
                $inquiry = Inquiry::find()->where(['quotation_staff' => $model->id])->andWhere(['IN', 'status', $type])->all();
                return count($inquiry);
            },
        ];
    }

    public function afterFind(){
        parent::afterFind();
        $type=[InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED,InquiryStatusTypes::AMENDED];
        $pending_type=[InquiryStatusTypes::IN_QUOTATION,InquiryStatusTypes::AMENDED];
        $inquiry = Inquiry::find()->where(['quotation_manager' => $this->id])->andWhere(['IN', 'status', $type])->all();
        $this->quotation_manager_count = $this->username . ' (' . count($inquiry) .')';
        $inquiry = Inquiry::find()->where(['follow_up_head' => $this->id])->andWhere(['IN', 'status', $type])->all();
        $this->followup_head_count = $this->username . ' (' . count($inquiry) .')';
        $inquiry = Inquiry::find()->where(['inquiry_head' => $this->id])->andWhere(['IN', 'status', $type])->orWhere(['follow_up_head' => $this->id])->andWhere(['IN', 'status', $type])->all();
        $this->head_count = $this->username . ' (' . count($inquiry) .')';
        $inquiry = Inquiry::find()->where(['quotation_staff' => $this->id])->andWhere(['IN', 'status', $type])->orWhere(['follow_up_staff' => $this->id])->andWhere(['IN', 'status', $type])->all();
        $this->quotation_staff_count = $this->username . ' (' . count($inquiry) .')';
        $inquiry = Inquiry::find()->where(['follow_up_staff' => $this->id])->andWhere(['IN', 'status', $type])->orWhere(['follow_up_staff' => $this->id])->andWhere(['IN', 'status', $type])->all();
        $this->followup_staff_count = $this->username . ' (' . count($inquiry) .')';
        $inquiry = Inquiry::find()->where(['quotation_manager' => $this->id])->andWhere(['IN', 'status', $pending_type])->all();
        $this->quotation_manager_c = count($inquiry);
        $inquiry = Inquiry::find()->where(['inquiry_head' => $this->id])->andWhere(['IN', 'status', $pending_type])->all();
        $this->head_c = count($inquiry);
        $inquiry = Inquiry::find()->where(['quotation_staff' => $this->id])->andWhere(['IN', 'status', $pending_type])->all();
        $this->quotation_staff_c = count($inquiry);
    }

    /**
     * get all quotation manager with no_of_inquiries assigned
     */
    public static function getQuotationManager()
    {
        $data =  static::find()->where(['role' => UserTypes::QUOTATION_MANAGER, 'status' => 10])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','quotation_manager_count'); //id = your ID model, name = your caption
        return $value;
    }

    /**
     * get all head users(except staff) with no_of_inquiries assigned
     */
    public static function getHead()
    {
        $data =  static::find()->where(['status' => 10])->andWhere(['<>', 'role', UserTypes::BOOKING_STAFF])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','head_count'); //id = your ID model, name = your caption
        return $value;
    }

    /**
     * get all followup staff with no_of_inquiries assigned
     */
    public static function getFollowupStaff($head='')
    {
        if($head!='')
            $data =  static::find()->where(['role' => UserTypes::FOLLOW_UP_STAFF, 'head' => $head, 'status' => User::STATUS_ACTIVE])->all();
        else
            $data =  static::find()->where(['role' => UserTypes::FOLLOW_UP_STAFF, 'status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','followup_staff_count'); //id = your ID model, name = your caption
        return $value;
    }

    /**
     * get all quotation staff with no_of_inquiries assigned
     */
    public static function getQuotationStaff($head='')
    {
        if($head!='')
            $data =  static::find()->where(['role' => UserTypes::QUOTATION_STAFF, 'head' => $head, 'status' => User::STATUS_ACTIVE])->all();
        else
        $data =  static::find()->where(['role' => UserTypes::QUOTATION_STAFF, 'status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','quotation_staff_count'); //id = your ID model, name = your caption
        return $value;
    }

    /**
 * get all followup managers with no_of_inquiries assigned
 */
    public static function getFollowupManager()
    {
        $data =  static::find()->where(['role' => UserTypes::FOLLOW_UP_MANAGER, 'status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','followup_head_count'); //id = your ID model, name = your caption
        return $value;
    }

    /**
     * get all booking staff
     */
    public static function getBookingStaff()
    {
        $data =  static::find()->where(['role' => UserTypes::BOOKING_STAFF, 'status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','username');
        return $value;
    }




   /* public static function getHeads()
    {
        $data =  static::find()->where(['IN', 'role', [UserTypes::FOLLOW_UP_MANAGER,UserTypes::QUOTATION_MANAGER,UserTypes::ADMIN]])->andWhere(['status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','username'); //id = your ID model, name = your caption
        return $value;
    }*/

    /**
     * get all users
     */
    public static function getHeads()
    {
        $data =  static::find()->where(['<>', 'role', UserTypes::BOOKING_STAFF])->andWhere(['status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','username'); //id = your ID model, name = your caption
        return $value;
    }
    /**
     * get all followup managers
     */
    public static function getFollowupManagers()
    {
        $data =  static::find()->where(['role' => UserTypes::FOLLOW_UP_MANAGER, 'status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','username'); //id = your ID model, name = your caption
        return $value;
    }

    /**
     * get all quotation managers
     */
    public static function getQuotationManagers()
    {
        $data =  static::find()->where(['role' => UserTypes::QUOTATION_MANAGER, 'status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','username'); //id = your ID model, name = your caption
        return $value;
    }

    /**
     * get all followup staff
     */
    public static function getAllFollowupStaff()
    {
        $data =  static::find()->where(['role' => UserTypes::FOLLOW_UP_STAFF, 'status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','username'); //id = your ID model, name = your caption
        return $value;
    }

    /**
     * get all quotation staff
     */
    public static function getAllQuotationStaff()
    {
        $data =  static::find()->where(['role' => UserTypes::QUOTATION_STAFF, 'status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','username'); //id = your ID model, name = your caption
        return $value;
    }


    /**
     * get all admins
     */
    public static function getAdmins()
    {
        $data =  static::find()->where(['role' => UserTypes::ADMIN, 'status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','username'); //id = your ID model, name = your caption
        return $value;
    }

    /**
     * get all users
     */
    public static function getAllUsers()
    {
        $data =  static::find()->where(['status' => User::STATUS_ACTIVE])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','username'); //id = your ID model, name = your caption
        return $value;
    }



}
