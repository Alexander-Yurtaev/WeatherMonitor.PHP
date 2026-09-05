<?php

declare(strict_types=1);

namespace AlexanderYurtaev\WeatherMonitor\helpers;

use AlexanderYurtaev\WeatherMonitor\exceptions\InvalidArgumentException;
use DateMalformedStringException;
use DateTime;
use Dotenv\Dotenv;
use Throwable;

class Utils
{
    /**
     * @throws InvalidArgumentException
     * @throws DateMalformedStringException
     */
    public static function GetTimeFromDate(string $str) : string
    {
        if (date_parse($str))
        {
            return new DateTime($str)->format('H:i');
        }
        throw new InvalidArgumentException($str);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function GetNumber(string|float $str) : string
    {
        if (!isset($str) || !is_numeric($str)){
            throw new InvalidArgumentException($str);
        }

        $value = (float)$str;
        return strval(round($value));
    }

    public static function GetImageTag(?string $url, string $emoji, string $alt) : string
    {
        if (isset($url))
        {
            return '<img src="' . $url . '" alt="' . $alt . '">';
        }
        return $emoji;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function ConvertKmH2Ms(mixed $str) : string
    {
        if (!isset($str) || !is_numeric($str)){
            throw new InvalidArgumentException($str);
        }

        $wind_kph = (float)$str;
        return strval(round($wind_kph*1000/60/60, 1));
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

    /**
     * @throws DateMalformedStringException
     */
    public static function GetHour(string $date) : string
    {
        $now = new DateTime($date);
        return $now->format('H');
    }

    /**
     * @throws InvalidArgumentException
     */
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

    public static function CallFunctionSafe(callable $func, string $default = ''): string
    {
        try {
            return $func();
        }
        catch (Throwable) {
            return $default;
        }
    }

    public static function DomainCanReceiveMail(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $domain = substr(strrchr($email, "@"), 1);
        return dns_check_record($domain);
    }
}