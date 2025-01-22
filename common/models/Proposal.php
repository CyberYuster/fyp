<?php

namespace common\models;

use common\models\query\ProposalQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%proposal}}".
 *
 * @property string $id
 * @property string $title
 * @property string|null $description
 * @property string $file_name
 * @property int $visibility
 * @property string $created_at
 * @property string|null $updated_at
 * @property int $created_by
 * @property string $type
 */
class Proposal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    const VISIBILITY_PRIVATE = 0;
    const VISIBILITY_PUBLIC = 1;
    const VISIBILITY_DEPARTMENT = 2;
    const VISIBILITY_COLLEGE = 3;
    public $file;

    public static function tableName()
    {
        return '{{%proposal}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'title', 'file_name', 'type'], 'required'],
            [['description', 'type'], 'string'],
            [['visibility', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'title', 'file_name', 'type'], 'string', 'max' => 512],
            [['created_at', 'updated_at'], 'string', 'max' => 100],
            [['file'], 'file', 'extensions' => ['pdf, docx', 'doc'], 'message' => 'The proposal file should be a pdf or docx format!'],
            [['id'], 'unique'],
            ['visibility', 'default', 'value' => self::VISIBILITY_PRIVATE],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
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
            'file_name' => 'File Name',
            'visibility' => 'Visibility',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'type' => 'Type',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaffs()
    {
        return $this->hasOne(Staffs::class, ['id' => 'created_by']);
    }


    /**
     * {@inheritdoc}
     * @return ProposalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProposalQuery(get_called_class());
    }

    public function getProposalLink()
    {
        return Yii::$app->params['frontendUrl'].'uploads/proposals'.$this->id.'pdf';
    }

    public function getVisibilityLabels()
    {
        return [
            self::VISIBILITY_PRIVATE => 'Private',
            self::VISIBILITY_PUBLIC => 'Public',
            self::VISIBILITY_DEPARTMENT => 'Department',
            self::VISIBILITY_COLLEGE => 'College'
        ];
    }

    

    public function getVisibilityLabel()
    {
        if ($this->visibility == self::VISIBILITY_PRIVATE) {
            return 'Private';
        } elseif ($this->visibility == self::VISIBILITY_PUBLIC) {
            return 'Public';
        } elseif ($this->visibility == self::VISIBILITY_DEPARTMENT) {
            return 'Department';
        } elseif ($this->visibility == self::VISIBILITY_COLLEGE) {
            return 'College';
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        if (file_exists($this->file_name)) {
            unlink($this->file_name);
        }
    }

    public function getCreatorDepartment()
    {
        // Retrieve the creator's ID from the created_by field
        $creatorId = $this->created_by;

        // Find the user with the specified ID
        $creator = User::findOne($creatorId);

        $staff = Staffs::find()->where(['user_id'=>$creator])->one();

        // If the creator is found, return their department; otherwise, return null
        return $creator ? $staff->department_id : null;
    }

}
