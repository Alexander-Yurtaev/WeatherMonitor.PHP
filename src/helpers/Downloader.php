<?php
namespace WeatherMonitor\helpers;

class Downloader
{
    function load(string $url)
    {
        // 1. Инициализируем сеанс cURL
        $ch = curl_init();

        // 2. Устанавливаем основные опции
        curl_setopt($ch, CURLOPT_URL, $url);

        // Возвращать результат как строку, а не выводить сразу в браузер
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Следовать редиректам (если сервер ответит 301 или 302)
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Установить таймаут выполнения (в секундах)
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        // Установить понятный User-Agent, чтобы сайт не принял запрос за бота
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (PHP)');

        // Закрываем curl сразу после получения данных
        curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);

        // Прописываем сертификат
        $certPath = __DIR__ . '/../../config/cacert.pem'; // Путь относительно Downloader.php
        if (file_exists($certPath)) {
            curl_setopt($ch, CURLOPT_CAINFO, $certPath);
        }
        $response = curl_exec($ch);

        // 3. Выполняем запрос
        $response = curl_exec($ch);

        // Проверяем наличие ошибок сети или протокола
        if ($response === false) {
            throw new \Exception('Ошибка cURL: ' . curl_error($ch));
        } else {
            // Получаем код ответа сервера (например, 200, 404, 500)
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode >= 200 && $httpCode < 300) {
                // Декодируем JSON-ответ в ассоциативный массив
                return json_decode($response, true);
            } else {
                throw new \Exception("Сервер вернул ошибку: " . $httpCode);
            }
        }
    }
}