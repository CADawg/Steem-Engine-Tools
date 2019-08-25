<?php
/**
 * Created by PhpStorm.
 * User: Conor Howland
 * Date: 06/08/2019
 * Time: 14:45
 */


/**
 * Convert epoch to readable time
 * @param int $epoch Epoch Time To Convert
 * @param bool $milli Millisecond precision
 * @param bool $detailed Show full time
 * @return false|string Result Text
 */

function epoch_to_time($epoch = 0, $milli = false, $detailed = false) {
    if ($epoch == "") return "";
    if (!$detailed) {return time_difference_string($epoch, $milli);}

    try {
        if ($milli) {
            $epoch = $epoch / 1000;
        }

        date_default_timezone_set('GMT');
        return date('c', $epoch);
    } catch (Exception $e) {
        return "";
    }
}

/**
 * Time to time difference string
 * @param int $datetime Epoch Time to use
 * @param bool $milli Has milliseconds
 * @return string Time Difference Specified as till/ago
 */
function time_difference_string($datetime, $milli = true) {
    $datetime = $datetime / ($milli ? 1000 : 1);
    $future = $datetime > time();

    $now = new DateTime;
    $ago = new DateTime("@$datetime");
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    $string = array_slice($string, 0, 1);
    if ($future) {
        return $string ? "in " . implode(', ', $string) : 'just now';
    } else {
        return $string ? implode(', ', $string) . " ago" : 'just now';
    }
}