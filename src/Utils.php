<?php

namespace WeatherMonitor;

class Utils
{
    public function GetTimeFromDate(string $str, string $default) : string
    {
        if (isset($str))
        {
            $updateDate = date_parse($str);
            if ($updateDate)
            {
                return $updateDate['hour'] . ':' . $updateDate['minute'];
            }
            return $default . '_1';
        }
        return $default . '_2';
    }

    public function GetNumber(string $str, string $default) : string
    {
        if (!isset($str) || !is_numeric($str)) return $default;
        $value = (float)$str;
        return bcround($value, 0);
    }

    public function GetImageTag(string $url, string $emoji) : string
    {
        if (isset($url))
        {
            return '<img src="' . $url . '" />';
        }
        return $emoji;
    }

    public function ConvertKmH2Ms(mixed $str, string $default) : string
    {
        if (!isset($str) || !is_numeric($str)) return $default;
        $wind_kph = (float)$str;
        return bcround($wind_kph*1000/60/60, 1);
    }
}