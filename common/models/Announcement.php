<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%announcement}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $to
 * @property string|null $type
 * @property string|null $due_date
 * @property int|null $academicyear_id
 * @property string|null $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Announcement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%announcement}}';
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
            [['due_date'], 'safe'],
            [['name', 'description', 'to', 'type'], 'required'],
            [['academicyear_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'description', 'to', 'type','status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'to' => 'To',
            'type' => 'Type',
            'due_date' => 'Due Date',
            'academicyear_id' => 'Academic year',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
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
