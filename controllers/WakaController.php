<?php

namespace app\controllers;

use app\models\Duration;
use app\models\Heartbeat;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;

class WakaController extends Controller
{
    public function actionIndex()
    {
        $models = Heartbeat::find()
            ->byProject('app')
            ->byDate('2016-08-02')
//            ->byDate('2016-08-03')
//            ->byDateRange('2016-08-02', '2016-08-03')
//            ->andWhere(['entity' => [
//                '/Users/deanar/b2cm/app/tests/codeception/functional/BaseRestCest.php',
//                '/Users/deanar/b2cm/app/tests/codeception/functional/RbacCest.php',
//            ]])
            ->all();


        return $this->render('index', compact('models'));
    }

    public function actionChart()
    {

        $models = Duration::find()
            ->select([new Expression('FROM_UNIXTIME(`time` + 3600*3, \'%Y-%m-%d\') as day'), 'round(sum(duration)) as duration', 'project'])
            ->groupBy(['day', 'project'])
            ->orderBy([
                'time'    => SORT_ASC,
                'project' => SORT_ASC,
            ])
            ->asArray()
            ->all();


        $projects = array_unique(ArrayHelper::getColumn($models, 'project'));

        $dates =
            array_map(function ($value) {
                return strtotime($value);
            }, ArrayHelper::getColumn($models, 'day'));

        $date_start = min($dates);
        $date_end = max($dates);


        $dates_array = [];
        $current_date = $date_start;

        while ($current_date <= $date_end) {
            array_push($dates_array, date('Y-m-d', $current_date));
            $current_date = strtotime('+1 day', $current_date);
        }

        $data = ArrayHelper::map($models, 'day', 'duration', 'project');


        $result = [];


        $result[0] = ['day'];
        foreach ($projects as $project) {
            array_push($result[0], $project);
        }

        foreach ($dates_array as $date) {

            $array = [$date];


            foreach ($projects as $project) {
                $duration = null;
                if (isset($data[$project][$date])) {
                    $duration = round(intval($data[$project][$date]) / 3600, 2);
                }
                array_push($array, $duration);
            }

            array_push($result, $array);
        }

        VarDumper::dump($result);

        return $this->render('chart', compact('result'));
    }
}
