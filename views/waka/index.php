<?php
/**
 * Created by PhpStorm.
 * User: deanar
 * Date: 04/08/16
 * Time: 16:21
 */
/**
 * @var \app\models\Heartbeat[] $models
 */
$gap = 5 * 60;

$prev_time = 0;
$total = 0;

$durations = [];
$i = 0;

foreach ($models as $model) {
    echo $model->time;


    echo ' - ' . $model->entity;

    echo '<br>';

    echo Yii::$app->formatter->asDatetime($model->time);
    echo '<br>';

    $diff = $model->time - $prev_time;
    echo $diff;

    if ($i > 0) {
        if ($diff < $gap) {
            $plus = $diff;
        } else {
            $plus = $gap;
        }
    } else {
        $plus = 0;
    }
    echo ' ( ' . $plus . ' )';

    $total += $plus;

    $prev_time = $model->time;
    echo '<br>';

    echo '<hr>';

    $i++;
}

$total += $gap;

echo $total;
echo '<br>';
echo $total / 60;
//1470171986 - 1470171644 = 342;