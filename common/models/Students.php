<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "students".
 *
 * @property int $id
 * @property string $firstname
 * @property string|null $secondname
 * @property string $lastname
 * @property string $email
 * @property string $reg_no
 * @property string $phone_number
 * @property string $gender
 * @property int $yos
 * @property string $type
 * @property int|null $academicyear_id
 * @property string $department_id
 * @property string $program_id
 * @property string|null $supervisor_id
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 */
class Students extends \yii\db\ActiveRecord
{
    const STATUS_MALE = 'Male';
    const STATUS_FEMALE = 'Female';
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 'InActive';
    const STATUS_ACTIVE = 'Active';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'students';
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
            [['firstname', 'lastname', 'email', 'reg_no', 'phone_number', 'program_id'], 'trim'],
            [['firstname', 'lastname', 'email', 'reg_no', 'phone_number', 'gender', 'yos','academicyear_id','type','department_id', 'program_id', 'status'], 'required'],
            [['yos', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            ['email', 'email'],
            ['reg_no', 'unique', 'targetClass' => '\common\models\Students', 'message' => 'This Registration Number has already been registered.'],
            ['email', 'unique', 'targetClass' => '\common\models\Students', 'message' => 'This email address has already been registered.'],
            [['firstname', 'secondname', 'lastname', 'email', 'reg_no', 'phone_number', 'gender', 'program_id', 'status'], 'string', 'max' => 255],
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
            'reg_no' => 'Reg No',
            'phone_number' => 'Phone Number',
            'gender' => 'Gender',
            'yos' => 'Yos',
            'type' => 'Type',
            'academicyear_id' => 'Academic year',
            'department_id' => 'Department Name',
            'program_id' => 'Program Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
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
     * Gets query for [[Program]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::class, ['id' => 'program_id']);
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
     * Gets query for [[Academicyear]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicyear()
    {
        return $this->hasOne(Academicyears::class, ['id' => 'academicyear_id']);
    }
}
