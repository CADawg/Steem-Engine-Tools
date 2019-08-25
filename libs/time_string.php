<?php
/**
 * Created by PhpStorm.
 * User: Conor Howland
 * Date: 06/08/2019
 * Time: 14:45
 */

function epoch_to_time($epoch, $milli=true, $detailed=false, $future = false) {
    if ($epoch == "") {
        return "";
    }
    if (!$detailed) {
        if ($future) {
            return time_difference_string($epoch, $milli);
        } else {
            return time_difference_string($epoch, $milli);
        }
    }

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

function time_difference_string($datetime, $milli = true,$full = false) {
    if ($milli) {
        $datetime = $datetime / 1000;
    }

    $future = false;
    if ($datetime > time()) {
        $future = true;
    }

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

    if (!$full) $string = array_slice($string, 0, 1);
    if ($future) {
        return $string ? "in " . implode(', ', $string) : 'just now';
    } else {
        return $string ? implode(', ', $string) . " ago" : 'just now';
    }
}