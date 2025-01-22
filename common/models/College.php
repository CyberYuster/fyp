<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%college}}".
 *
 * @property int $id
 * @property string $college_name
 * @property string $college_code
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Department[] $departments
 */
class College extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%college}}';
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
            [['college_name', 'college_code'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['college_name', 'college_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'college_name' => 'College Name',
            'college_code' => 'College Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Departments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Department::class, ['college_id' => 'id']);
    }
}
