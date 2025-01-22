<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%department}}".
 *
 * @property int $id
 * @property string|null $department_name
 * @property string|null $department_code
 * @property string|null $status
 * @property int|null $college_id
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Staffs[] $staffs
 * @property College $college
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%department}}';
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
            [['status'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['department_name', 'department_code'], 'string', 'max' => 255],
            [['college_id'], 'exist', 'skipOnError' => true, 'targetClass' => College::class, 'targetAttribute' => ['college_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'department_name' => 'Department Name',
            'department_code' => 'Department Code',
            'college_id' => 'College Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Staffs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaffs()
    {
        return $this->hasMany(Staffs::class, ['department_id' => 'id']);
    }

    /**
     * Gets query for [[College]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCollege()
    {
        return $this->hasOne(College::class, ['id' => 'college_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\DepartmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\DepartmentQuery(get_called_class());
    }
}
