<?php

namespace WeatherMonitor\helpers;

use DateTime;
use Dotenv\Dotenv;
use WeatherMonitor\exceptions\InvalidArgumentException;

class Utils
{
    public static function GetTimeFromDate(string $str, string $default) : string
    {
        if (date_parse($str))
        {
            return new \DateTime($str)->format('H:i');
        }
        return $default;
    }

    public static function GetNumber(string $str, string $default) : string
    {
        if (!isset($str) || !is_numeric($str)) return $default;
        $value = (float)$str;
        return bcround($value, 0);
    }

    public static function GetImageTag(string $url, string $emoji) : string
    {
        if (isset($url))
        {
            return '<img src="' . $url . '" />';
        }
        return $emoji;
    }

    public static function ConvertKmH2Ms(mixed $str, string $default) : string
    {
        if (!isset($str) || !is_numeric($str)) return $default;
        $wind_kph = (float)$str;
        return bcround($wind_kph*1000/60/60, 1);
    }

    public static function GetValueFromEnv(string $key) : string|bool
    {
        if (file_exists(__DIR__ . '/../../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $env = $dotenv->load();
            return $env[$key] ?? false;
        }
        return false;
    }

    public static function GetHour(string $date) : int
    {
        $now = new DateTime($date);
        return $now->format('H');
    }

    public static function GetWeekdayName(string $str) : string
    {
        $timestamp = strtotime($str);
        if ($timestamp === false) {
            throw new InvalidArgumentException('Неверный формат даты');
        }

        $daysRu = [
            0 => 'Вс',
            1 => 'Пн',
            2 => 'Вт',
            3 => 'Ср',
            4 => 'Чт',
            5 => 'Пт',
            6 => 'Сб',
        ];

        return $daysRu[(int)date('w', $timestamp)] ?? '?';
    }
}