<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%program}}".
 *
 * @property int $id
 * @property string|null $program_name
 * @property string|null $program_code
 * @property int|null $department_id
 * @property string|null $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Department $department
 */
class Program extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%program}}';
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::class,
//            BlameableBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['id', 'created_at', 'updated_at'], 'required'],
            [['id', 'department_id', 'created_at', 'updated_at'], 'integer'],
            [['status'], 'string'],
            [['program_name', 'program_code'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['department_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'program_name' => 'Program Name',
            'program_code' => 'Program Code',
            'department_id' => 'Department Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
}
