<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%staffs}}".
 *
 * @property int $id
 * @property string|null $firstname
 * @property string|null $secondname
 * @property string|null $lastname
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $gender
 * @property string|null $edu_levels
 * @property int|null $user_id
 * @property string|null $employee_id
 * @property string|null $department_id
 * @property string|null $status
 *
 * @property User $user
 */
class Staffs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%staffs}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'email', 'department_id', 'phone_number', 'employee_id'], 'trim'],
            [['firstname', 'lastname', 'email', 'employee_id', 'phone_number','edu_levels', 'gender', 'department_id', 'status'], 'required'],
            [['created_at', 'updated_at', 'created_by', 'updated_by',  'department_id'], 'integer'],
            [['gender', 'status', 'firstname', 'secondname', 'lastname', 'email', 'phone_number', 'employee_id'], 'string', 'max' => 255],
            [['employee_id'], 'unique', 'message' => 'This Employee number has already been registered.'],
            [['email'], 'unique', 'message' => 'This email address has already been registered.'],
            [['email'], 'email'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'secondname' => 'Secondname',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'phone_number' => 'Phone Number',
            'gender' => 'Gender',
            'user_id' => 'User ID',
            'employee_id' => 'Employee ID',
            'department_id' => 'Department ID',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'department_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\StaffsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\StaffsQuery(get_called_class());
    }
}
