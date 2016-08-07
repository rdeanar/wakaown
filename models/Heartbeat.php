<?php

namespace app\models;

use Yii;
use \app\models\base\Heartbeat as BaseHeartbeat;

/**
 * This is the model class for table "heartbeat".
 */
class Heartbeat extends BaseHeartbeat
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge([
            [['is_debugging', 'is_write'], 'filter', 'filter' => 'intval'],
        ], parent::rules());
    }

    public static function createFromApi($attributes)
    {
        $model = new self();
        $model->setAttributes($attributes);

        
        $time = explode('.', $attributes['time']);

        $model->time = $time[0];
        $model->time_micro = isset($time[1]) ? $time[1] : 0;


        try {
            if (!$model->save()) {
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
