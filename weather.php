<?php
require_once __DIR__ . '/vendor/autoload.php';

include_once __DIR__ . '/incs/header.tpl.php';
?>

    <div class="app-card" id="app">
        <div class="screen active" id="weatherScreen">
            <!-- Состояние ошибки -->
            <div class="state-overlay" id="errorState">
                <i class="bi bi-exclamation-circle" style="font-size:52px; color:#ffb4a2; margin-bottom:12px;"></i>
                <div class="state-title">Не удалось загрузить</div>
                <div class="state-sub">Проверьте соединение или попробуйте позже</div>
                <button class="btn-retry" id="retryBtn">
                    <i class="bi bi-arrow-repeat" style="font-size:20px;"></i> Повторить
                </button>
            </div>

            <!-- Основной контент -->
            <div class="weather-content" id="weatherContent">

                <!-- Шапка -->
                <div class="city-header">
                    <div class="city-name">
                        <i class="bi bi-geo-alt"></i>
                        <span id="cityName">--</span>
                    </div>
                    <div class="update-badge" id="updateTime">Обновлено: сейчас</div>
                </div>

                <!-- Текущая погода -->
                <div class="current-weather" id="currentWeather">
                    <div class="current-temp-row">
                        <div>
                            <div class="temp-big" id="currentTemp">--<sup>°C</sup></div>
                            <div class="current-desc" id="currentDesc">
                                <span class="badge" id="conditionText">--</span>
                                <span class="badge">
                  <i class="bi bi-thermometer-half"></i> ощущается <span id="feelsLike">--</span>°C
                </span>
                            </div>
                        </div>
                        <div class="weather-icon-big" id="currentIcon">☀️</div>
                    </div>
                    <div class="extra-row">
                        <div class="extra-item"><i class="bi bi-droplet"></i> Влажность: <span id="humidity">--</span>%
                        </div>
                        <div class="extra-item"><i class="bi bi-wind"></i> Ветер: <span id="windSpeed">--</span> м/с
                        </div>
                        <div class="extra-item"><i class="bi bi-eye"></i> <span id="visiblity">--</span> км</div>
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