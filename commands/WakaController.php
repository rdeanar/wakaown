<?php
/**
 * Created by PhpStorm.
 * User: deanar
 * Date: 03/08/16
 * Time: 16:19
 */

namespace app\commands;

use app\components\WakaDataGrabber;

class WakaController extends \yii\console\Controller
{
    public function actionGrab()
    {
        WakaDataGrabber::grabLastWeek();
    }
}