<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%supervisor_assignment}}".
 *
 * @property int $id
 * @property int $supervisor_id
 * @property int $thesis_id
 * @property string|null $activation
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $supervisor
 * @property Thesis $thesis
 */
class Assignment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%supervisor_assignment}}';
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
            [['supervisor_id', 'thesis_id'], 'required'],
            [['activation'], 'string'],
            [['supervisor_id', 'thesis_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['supervisor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['supervisor_id' => 'id']],
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
            'supervisor_id' => 'Supervisor Name',
            'thesis_id' => 'Thesis Title',
            'activation' => 'Activation',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
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
}
