<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%answer}}".
 *
 * @property int $id
 * @property string|null $description
 * @property string|null $attachment
 * @property int|null $task_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Task $task
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%answer}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
//            BlameableBehavior::class
        ];
    }
    public $file;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'task_id', 'created_at', 'updated_at'], 'integer'],
            [['attachment'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['file'], 'file', 'extensions' => ['docx', 'doc'], 'message' => 'The document should be word document.'], // Limit to Word documents
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'attachment' => 'Attachment',
            'file' => 'Attachment',
            'task_id' => 'Task',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
