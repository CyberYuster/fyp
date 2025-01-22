<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%presentation_feedback}}".
 *
 * @property int $id
 * @property int|null $thesis_id
 * @property int $submission_id
 * @property string|null $attachment
 * @property string|null $description
 * @property string|null $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Submissions $submission
 * @property Thesis $thesis
 */
class Presentationfeedback extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%presentation_feedback}}';
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
            [['thesis_id', 'submission_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['submission_id'], 'required'],
            [['status'], 'string'],
            [['attachment', 'description'], 'string', 'max' => 255],
            [['thesis_id'], 'exist', 'skipOnError' => true, 'targetClass' => Thesis::class, 'targetAttribute' => ['thesis_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submissions::class, 'targetAttribute' => ['submission_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thesis_id' => 'Thesis ID',
            'submission_id' => 'Submission ID',
            'attachment' => 'Attachment',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[Submission]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubmission()
    {
        return $this->hasOne(Submissions::class, ['id' => 'submission_id']);
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
