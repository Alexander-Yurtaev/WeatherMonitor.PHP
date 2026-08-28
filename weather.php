<?php

require_once __DIR__ . '/vendor/autoload.php';

use WeatherMonitor\Downloader;
use WeatherMonitor\Utils;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $lat = isset($_GET["lat"]) && is_numeric($_GET["lat"]) ? +$_GET["lat"] : 0;
    $lon = isset($_GET["lon"]) && is_numeric($_GET["lon"]) ? +$_GET["lon"] : 0;
    $api_key = Utils::GetValueFromEnv('WEATHER_API_KEY');
    if ($api_key === false) {
        echo 'Не задан api-ключ.';
        die();
    }

    $url = 'https://api.weatherapi.com/v1/forecast.json?key=' . $api_key . '&q=' . $lat . ',' . $lon . '&days=3&lang=ru';
    $downloader = new Downloader();
    $hasError = false;
    try {
        $data = $downloader->load($url);
    } catch (\Exception $e) {
        $hasError = true;
    }

    if (!$hasError) {
        $cityName = $data['location']['name'];
        $updateTime = Utils::GetTimeFromDate($data['current']['last_updated'], 'сейчас');
        $currentTemperature = Utils::GetNumber($data['current']['temp_c'], '--');
        $conditionText = $data['current']['condition']['text'] ?? '--';
        $feelsLike = Utils::GetNumber($data['current']['feelslike_c'], '--');
        $currentIcon = Utils::GetImageTag($data['current']['condition']['icon'], '☀️');
        $humidity = Utils::GetNumber($data['current']['humidity'], '--');
        $humidity = Utils::GetNumber($data['current']['humidity'], '--');

        $windSpeed = Utils::ConvertKmH2Ms($data['current']['wind_kph'], '--');
        $visiblity = Utils::GetNumber($data['current']['vis_km'], '--');

        $forecastDay = [];
        if (isset($data['forecast']) && is_array($data['forecast']['forecastday'])) {
            $forecastDay = $data['forecast']['forecastday'];
        }
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
                        <span id="cityName"><?= $cityName ?></span>
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
                        <div class="extra-item"><i class="bi bi-droplet"></i> Влажность: <span
                                    id="humidity"><?= $humidity ?></span>%
                        </div>
                        <div class="extra-item"><i class="bi bi-wind"></i> Ветер: <span
                                    id="windSpeed"><?= $windSpeed ?></span> м/с
                        </div>
                        <div class="extra-item"><i class="bi bi-eye"></i> <span id="visiblity"><?= $visiblity ?></span>
                            км
                        </div>
                    </div>
                </div>

                <!-- Почасовой прогноз -->
                <div class="section-title">
                    <i class="bi bi-clock"></i> Почасовой
                </div>
                <div class="hourly-scroll" id="hourlyContainer">
                    <?php if (count($forecastDay) == 0): ?>
                        <div class="text-center text-white-50 w-100" style="padding: 20px;">
                            Нет данных о почасовой погоде
                        </div>
                    <?php else: ?>
                        <?php foreach ($forecastDay[0]['hour'] as $hour): ?>
                            <?php if (Utils::GetHour($data['current']['last_updated']) <= Utils::GetHour($hour['time'])): ?>
                                <div class="hour-item">
                                    <div class="hour-label"><?= Utils::GetTimeFromDate($hour['time'], '--') ?></div>
                                    <div class="hour-icon"><?= Utils::GetImageTag($hour['condition']['icon'], '') ?></div>
                                    <div class="hour-temp"><?= Utils::GetNumber($hour['temp_c'], '--') ?>°</div>
                                    <div class="hour-day-label">Сегодня</div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php foreach ($forecastDay[1]['hour'] as $hour): ?>
                            <div class="hour-item">
                                <div class="hour-label"><?= Utils::GetTimeFromDate($hour['time'], '--') ?></div>
                                <div class="hour-icon"><?= Utils::GetImageTag($hour['condition']['icon'], '') ?></div>
                                <div class="hour-temp"><?= Utils::GetNumber($hour['temp_c'], '--') ?>°</div>
                                <div class="hour-day-label">Завтра</div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Прогноз на 3 дня -->
                <div class="section-title" style="margin-top: 4px;">
                    <i class="bi bi-calendar3"></i> Прогноз на 3 дня
                </div>
                <div class="day-list" id="dayContainer">
                    <?php if (count($forecastDay) == 0): ?>
                        <div class="text-center text-white-50" style="padding: 20px;">
                            Нет данных о прогнозе
                        </div>
                    <?php else: ?>
                        <?php foreach ($forecastDay as $day): ?>
                            <div class="day-card">
                                <div class="day-name"><?= Utils::GetWeekdayName($day['date']) ?></div>
                                <div class="day-icon">
                                    <span><span class="desc-text"><?= Utils::GetImageTag($day['day']['condition']['icon'], '') ?></span></span>
                                    <span class="desc-text"><?= $day['day']['condition']['text'] ?></span>
                                </div>
                                <div class="day-temps">
                                    <span class="max-temp"><?= Utils::GetNumber($day['day']['maxtemp_c'], 0) ?>°</span>
                                    <span class="min-temp"><?= Utils::GetNumber($day['day']['mintemp_c'], 0) ?>°</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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