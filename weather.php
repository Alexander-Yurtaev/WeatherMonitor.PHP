<?php

use WeatherMonitor\Downloader;
use WeatherMonitor\Utils;

require_once __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $utils = new Utils();

    $lat = isset($_GET["lat"]) && is_numeric($_GET["lat"]) ? +$_GET["lat"] : 0;
    $lon = isset($_GET["lon"]) && is_numeric($_GET["lon"]) ? +$_GET["lon"] : 0;
    $api_key = 'api_key';
    $url = 'https://api.weatherapi.com/v1/forecast.json?key=' . $api_key . '&q=' . $lat . ',' . $lon . '&days=3';
    $downloader = new Downloader();
    $hasError = false;
    try {
        $data = $downloader->load($url);
    }
    catch (\Exception $e) {
        $hasError = true;
    }

    if(!$hasError)
    {
        $cityName = $data['location']['name'];
        $updateTime = $utils->GetTimeFromDate($data['current']['last_updated'], 'сейчас');
        $currentTemperature = $utils->GetNumber($data['current']['temp_c'], '--');
        $conditionText = isset($data['current']['condition']['text']) ? $data['current']['condition']['text'] : '--';
        $feelsLike = $utils->GetNumber($data['current']['feelslike_c'], '--');
        $currentIcon = $utils->GetImageTag($data['current']['condition']['icon'], '☀️');
        $humidity = $utils->GetNumber($data['current']['humidity'], '--');
        $humidity = $utils->GetNumber($data['current']['humidity'], '--');

        $windSpeed = $utils->ConvertKmH2Ms($data['current']['wind_kph'], '--');
        $visiblity = $utils->GetNumber($data['current']['vis_km'], '--');
    }
}

include_once __DIR__ . '/incs/header.tpl.php';
?>

    <div class="app-card" id="app">

        <!-- ======== ЭКРАН 2: ПРОГНОЗ ======== -->
        <div class="screen active" id="weatherScreen">

            <!-- Состояние загрузки -->
            <div class="state-overlay" id="loadingState">
                <div class="spinner"></div>
                <div class="state-title">Загрузка погоды</div>
                <div class="state-sub" id="loadingSub">Обновление данных...</div>
            </div>

            <!-- Состояние ошибки -->
            <div class="state-overlay<?= $hasError ? ' active' : '' ?>" id="errorState">
                <i class="bi bi-exclamation-circle" style="font-size:52px; color:#ffb4a2; margin-bottom:12px;"></i>
                <div class="state-title">Не удалось загрузить</div>
                <div class="state-sub">Проверьте соединение или попробуйте позже</div>
                <button class="btn-retry" id="retryBtn">
                    <i class="bi bi-arrow-repeat" style="font-size:20px;"></i> Повторить
                </button>
            </div>

            <!-- Основной контент -->
            <div class="weather-content<?= $hasError ? ' hidden' : '' ?>" id="weatherContent">

                <!-- Шапка -->
                <div class="city-header">
                    <div class="city-name">
                        <i class="bi bi-geo-alt"></i>
                        <span id="cityName"><?= $cityName?></span>
                    </div>
                    <div class="update-badge" id="updateTime">Обновлено: <?= $updateTime ?></div>
                </div>

                <!-- Текущая погода -->
                <div class="current-weather" id="currentWeather">
                    <div class="current-temp-row">
                        <div>
                            <div class="temp-big" id="currentTemp"><?= $currentTemperature ?><sup>°C</sup></div>
                            <div class="current-desc" id="currentDesc">
                                <span class="badge" id="conditionText"><?= $conditionText ?></span>
                                <span class="badge">
                  <i class="bi bi-thermometer-half"></i> ощущается <span id="feelsLike"><?= $feelsLike ?></span>°C
                </span>
                            </div>
                        </div>
                        <div class="weather-icon-big" id="currentIcon"><?= $currentIcon ?></div>
                    </div>
                    <div class="extra-row">
                        <div class="extra-item"><i class="bi bi-droplet"></i> Влажность: <span id="humidity"><?= $humidity ?></span>%</div>
                        <div class="extra-item"><i class="bi bi-wind"></i> Ветер: <span id="windSpeed"><?= $windSpeed ?></span> м/с</div>
                        <div class="extra-item"><i class="bi bi-eye"></i> <span id="visiblity"><?= $visiblity ?></span> км</div>
                    </div>
                </div>

                <!-- Почасовой прогноз -->
                <div class="section-title">
                    <i class="bi bi-clock"></i> Почасовой
                    <span class="info-badge" id="hourCount"></span>
                </div>
                <div class="hourly-scroll" id="hourlyContainer">
                    <!-- Заполняется динамически -->
                </div>

                <!-- Прогноз на 3 дня -->
                <div class="section-title" style="margin-top: 4px;">
                    <i class="bi bi-calendar3"></i> Прогноз на 3 дня
                </div>
                <div class="day-list" id="dayContainer">
                    <!-- Заполняется динамически -->
                </div>

                <!-- Кнопка назад -->
                <a href="index.php" class="btn-back" id="backToSettingsBtn" style="text-decoration: none;">
                    <i class="bi bi-arrow-left"></i> Назад к настройкам
                </a>
            </div>
        </div>
    </div>

<?php
include_once __DIR__ . '/incs/footer.tpl.php';