<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%schedule}}".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $presentation_date
 * @property int|null $thesis_id
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Academicyears $academicyear
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%schedule}}';
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
            [['presentation_date'], 'safe'],
            [['thesis_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['status', 'description'], 'string'],
            [['title', 'status'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['thesis_id'], 'exist', 'skipOnError' => true, 'targetClass' => Thesis::class, 'targetAttribute' => ['thesis_id' => 'id']],
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
            'description' => 'Description',
            'presentation_date' => 'Presentation Date',
            'thesis_id' => 'Thesis',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[Thesis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getThesis()
    {
        return $this->hasOne(Thesis::class, ['id' => 'thesis_id']);
    }
}
