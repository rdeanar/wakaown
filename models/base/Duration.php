<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "duration".
 *
 * @property string $entity
 * @property integer $time
 * @property double $duration
 * @property integer $user_id
 * @property string $branch
 * @property string $dependencies
 * @property integer $is_debugging
 * @property string $language
 * @property string $project
 * @property string $type
 * @property string $aliasModel
 */
abstract class Duration extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'duration';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity', 'time', 'duration'], 'required'],
            [['time', 'user_id', 'is_debugging'], 'integer'],
            [['duration'], 'number'],
            [['entity', 'branch', 'dependencies', 'language', 'project', 'type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entity' => Yii::t('app', 'Entity'),
            'time' => Yii::t('app', 'Time'),
            'duration' => Yii::t('app', 'Duration'),
            'user_id' => Yii::t('app', 'User ID'),
            'branch' => Yii::t('app', 'Branch'),
            'dependencies' => Yii::t('app', 'Dependencies'),
            'is_debugging' => Yii::t('app', 'Is Debugging'),
            'language' => Yii::t('app', 'Language'),
            'project' => Yii::t('app', 'Project'),
            'type' => Yii::t('app', 'Type'),
        ];
    }


    
    /**
     * @inheritdoc
     * @return \app\models\query\DurationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\DurationQuery(get_called_class());
    }


}
