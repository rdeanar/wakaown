<?php

namespace app\models;

use Yii;
use \app\models\base\Duration as BaseDuration;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "duration".
 */
class Duration extends BaseDuration
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge([
            [['time', 'is_debugging'], 'filter', 'filter' => 'intval'],
        ], parent::rules());
    }

    public static function createFromApi($attributes)
    {
        $model = new self();
        $model->setAttributes($attributes, false);
        $model->dependencies = implode(', ', $attributes['dependencies']);

        try {
            if (!$model->save()) {

                VarDumper::dump($model->toArray());

                return $model->getErrors();
            }
        } catch (\yii\db\IntegrityException $e) {
            return [];
        } catch (\Exception $e) {
            return [$e->getMessage()];
        }

        return [];
    }
}
