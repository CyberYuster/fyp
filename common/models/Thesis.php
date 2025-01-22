<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%thesis}}".
 *
 * @property int $id
 * @property string|null $title
 * @property string $category
 * @property string|null $description
 * @property string $type
 * @property int|null $supervisor_id
 * @property int|null $academicyear_id
 * @property int|null $department_id
 * @property int|null $thesis_status
 * @property int|null $user_id
 * @property string|null $concept
 * @property string|null $status
 * @property string $progress
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $supervisor
 * @property User $user
 */
class Thesis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%thesis}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }

    public $file;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'category', 'description'], 'required'],
            [['description','type'], 'string'],
            [[ 'user_id', 'academicyear_id', 'department_id','thesis_status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['title', 'category'], 'string', 'max' => 255],
            [['concept', 'status', 'progress'], 'string', 'max' => 200],
            [['file'], 'file', 'extensions' => ['docx', 'doc'], 'message' => 'The concept note should be word document.'], // Limit to Word documents
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
            'title' => 'Title',
            'category' => 'Category',
            'description' => 'Description',
            'user_id' => 'User ID',
            'concept' => 'Concept',
            'yos' => 'Yos',
            'type' => 'Type',
            'academicyear_id' => 'Academic year',
            'thesis_status' => 'Thesis Status',
            'status' => 'Status',
            'file' => 'Concept Note',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

//    /**
//     * Gets query for [[Supervisor]].
//     *
//     * @return \yii\db\ActiveQuery
//     */
//    public function getStaffs()
//    {
//        return $this->hasOne(Staffs::class, ['id' => 'supervisor_id']);
//    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
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
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'thesis_status']);
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
