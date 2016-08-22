<?php

namespace app\controllers;

use app\components\DateHelper;
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

        // TODO add timezone offset
        // (new \DateTime($date, new \DateTimeZone(getenv('TIMEZONE'))))->getOffset()

        $models = Duration::find()
            ->select([new Expression('FROM_UNIXTIME(`time` + 3600*3, \'%Y-%m-%d\') as day'), 'round(sum(duration)) as duration', 'project'])

//            ->byDateRange('2016-8-15', '2016-08-23')
//                ->byProject(['app', 'merge'])

            ->groupBy(['day', 'project'])
            ->orderBy([
                'time'    => SORT_ASC,
                'project' => SORT_ASC,
            ])
            ->asArray()
            ->all();


        $projects_list = array_unique(ArrayHelper::getColumn($models, 'project'));

        $raw_dates = ArrayHelper::getColumn($models, 'day');

        $dates_array = DateHelper::getDatesListWithoutSpaces($raw_dates);

        $duration_by_date_by_project = ArrayHelper::map($models, 'day', 'duration', 'project');
        $duration_by_project_by_date = ArrayHelper::map($models, 'project', 'duration', 'day');


        {
            $projects_array = [];

            $projects_durations = array_map(function ($value) {
                return array_sum($value);
            }, $duration_by_date_by_project);


            foreach ($projects_list as $project) {
                $projects_array[$project] = [
                    'id'    => null,
                    'name'  => $project,
                    'total' => $projects_durations[$project],
                ];
            }
        }

        {

            $logged_time_data = [];

            foreach ($dates_array as $day) {

                $array = [
                    'date'  => $day,
                    'name'  => Yii::$app->formatter->asDate($day),
                    'xAxis' => Yii::$app->formatter->asDate($day, 'd-MM'),
                ];


                $sum = isset($duration_by_project_by_date[$day]) ? array_sum($duration_by_project_by_date[$day]) : 0;

                $array['formatted'] = DateHelper::formatHoursSpent($sum);
                $array['total_seconds'] = $sum;
                $array['value'] = round($sum / 3600, 2);

                $logged_time_data[] = $array;
            }
        }

        {
            $time_by_project = [];

            foreach ($projects_list as $project) {
                $array = [];

                foreach ($dates_array as $date) {

                    $seconds = isset($duration_by_date_by_project[$project][$date]) ? $duration_by_date_by_project[$project][$date] : 0;

                    array_push($array, [
                        'date'          => $date,
                        'formatted'     => DateHelper::formatHoursSpent($seconds),
                        'name'          => \Yii::$app->formatter->asDate($date),
                        'project'       => $project,
                        'total_seconds' => $seconds,
                        'value'         => round($seconds / 3600, 2),
                    ]);
                }

                $time_by_project[$project] = $array;
            }


        }

        return $this->render('chart', compact('projects_list', 'logged_time_data', 'time_by_project', 'projects_array'));
    }
}
