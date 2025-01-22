<?php

namespace common\models;

use DateTime;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%task}}".
 *
 * @property int $id
 * @property string|null $subject
 * @property string|null $description
 * @property string|null $attachment
 * @property int|null $user_id
 * @property int|null $thesis_id
 * @property int|null $supervisor_id
 * @property string|null $status
 * @property string|null $stage
 * @property datetime|null $due_date
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $supervisor
 * @property Thesis $thesis
 * @property User $user
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%task}}';
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
            [['user_id', 'thesis_id', 'supervisor_id', 'created_at', 'updated_at'], 'integer'],
            [['status','stage'], 'string'],
            [['stage', 'due_date'], 'required'],
            [['subject', 'description', 'attachment'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => ['docx', 'doc'], 'message' => 'The document should be a word document.'], // Limit to Word documents
            [['supervisor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['supervisor_id' => 'id']],
            [['thesis_id'], 'exist', 'skipOnError' => true, 'targetClass' => Thesis::class, 'targetAttribute' => ['thesis_id' => 'id']],
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
            'subject' => 'Subject',
            'description' => 'Description',
            'attachment' => 'Attachment',
            'user_id' => 'User',
            'thesis_id' => 'Thesis Title',
            'supervisor_id' => 'Supervisor',
            'status' => 'Status',
            'due_date' => 'Due date',
            'file' => 'Attachment',
            'stage' => 'Stage',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Supervisor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaffs()
    {
        return $this->hasOne(Staffs::class, ['id' => 'supervisor_id']);
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
     * Define a relation to fetch all answers related to this task.
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::class, ['task_id' => 'id']);
    }


      /**
     * Retrieves the timestamp of the latest answer submission related to this task.
     * @return int|null The latest answer submission timestamp or null if no answers found.
     */
    public function getLatestAnswerTime()
    {
        // Initialize the latest time to null
        $latestTime = null;

        // Iterate through associated answers
        foreach ($this->answers as $answer) {
            // Compare and update the latestTime if this answer's created_at is later
            if ($latestTime === null || $answer->created_at > $latestTime) {
                $latestTime = $answer->created_at;
            }
        }

        return $latestTime;
    }
}
