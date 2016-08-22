<?php
/**
 * Created by PhpStorm.
 * User: deanar
 * Date: 22/08/16
 * Time: 15:56
 */

namespace app\components;


class DateHelper
{
    public static function formatHoursSpent($seconds)
    {
        $date = gmdate("H:i:s", $seconds);

        $date = explode(':', $date);


        $postfixes = [ // TODO add plurals
            'hrs',
            'mins',
            'secs',
        ];

        for ($i = 0; $i < 3; $i++) {
            if ($date[$i] > 0) {
                $date[$i] .= ' ' . $postfixes[$i];
            } else {
                unset($date[$i]);
            }
        }

        if (empty($date)) {
            return '0 secs';
        }

        return implode(' ', $date);
    }


    /**
     * Get date range without spaces
     *
     * @param $dates
     *
     * @return array
     */
    public static function getDatesListWithoutSpaces($dates)
    {
        $dates =
            array_map(function ($value) {
                return strtotime($value);
            }, $dates);

        $date_start = min($dates);
        $date_end = max($dates);


        $dates_without_spaces = [];
        $current_date = $date_start;

        while ($current_date <= $date_end) {
            array_push($dates_without_spaces, date('Y-m-d', $current_date));
            $current_date = strtotime('+1 day', $current_date);
        }

        return $dates_without_spaces;
    }
}