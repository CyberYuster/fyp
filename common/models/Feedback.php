<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $thesis_id
 * @property string|null $attachment
 * @property string|null $remarks
 * @property string|null $description
 * @property string|null $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Thesis $thesis
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%feedback}}';
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
            [['status','title','remarks'], 'string'],
            [['attachment', 'description'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => ['docx', 'doc','pdf'], 'message' => 'The file should be a word/pdf document.'], // Limit to Word documents
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
            'title' => 'Feedback Title',
            'thesis_id' => 'Thesis Title',
            'attachment' => 'Attachment',
            'description' => 'Description',
            'remarks' => 'Remarks',
            'status' => 'Status',
            'file' => 'Attachment',
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
