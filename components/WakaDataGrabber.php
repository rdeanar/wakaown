<?php
/**
 * Created by PhpStorm.
 * User: deanar
 * Date: 05/08/16
 * Time: 01:38
 */

namespace app\components;


use GuzzleHttp\Client as Guzzle;
use app\models\Duration;
use app\models\Heartbeat;
use Mabasic\WakaTime\WakaTime;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;


class WakaDataGrabber
{
    public static function grabLastWeek()
    {
        return self::grabLastDays(7);
    }

    public static function grabLastDays($days=1)
    {

        $dates_array = [];

        for ($i = 0; $i < $days; $i++) {

            if ($i == 0) {
                $date = 'now';
            } else {
                $date = '-' . $i . 'days';
            }

            array_push(
                $dates_array,
                (new \DateTime($date, new \DateTimeZone(getenv('TIMEZONE'))))->format('Y-m-d')
            );
        }

        self::grabDates($dates_array);
    }

    public static function grabDates($dates)
    {
        $wakatime = new WakaTime(new Guzzle, getenv('WAKATIME_API_KEY'));

        foreach ($dates as $date) {
            $hearbeats = $wakatime->heartbeats($date, 'time,entity,type,project,language,branch,is_write,is_debugging');

            foreach ($hearbeats['data'] as $data) {
                $errors = Heartbeat::createFromApi($data);

                if (!empty($errors)) {
                    VarDumper::dump($errors);
                }
            }


            $projects = $wakatime->durations($date);
            $projects = ArrayHelper::getColumn($projects['data'], 'project');


            foreach ($projects as $project) {

                $durations = $wakatime->durations($date, $project);

                foreach ($durations['data'] as $data) {

                    $errors = Duration::createFromApi($data);

                    if (!empty($errors)) {
                        VarDumper::dump($errors);
                    }
                }
            }

        }

    }
}