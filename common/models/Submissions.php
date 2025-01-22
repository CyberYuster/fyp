<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%submissions}}".
 *
 * @property int $id
 * @property int|null $thesis_id
 * @property string|null $submission_type
 * @property string|null $description
 * @property string|null $attachment
 * @property string|null $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property PresentationFeedback[] $presentationFeedbacks
 */
class Submissions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%submissions}}';
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
            [['thesis_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['status'], 'string'],
            [['submission_type', 'attachment'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => ['docx', 'doc'], 'message' => 'The proposal file should be word document.'], // Limit to Word documents
//            [['file2'], 'file', 'extensions' => ['docx', 'doc', 'pdf'], 'message' => 'The files should be a word or pdf document.'], // Limit to Word documents
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thesis_id' => 'Thesis',
            'submission_type' => 'Submission Type',
            'description' => 'Description',
            'attachment' => 'Attachment',
            'file' => 'File',
            'file2' => 'File',
            'status' => 'Status',
            'created_at' => 'Submitted At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[PresentationFeedbacks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPresentationFeedbacks()
    {
        return $this->hasMany(PresentationFeedback::class, ['submission_id' => 'id']);
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
