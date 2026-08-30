# Weather Monitor

Приложение для мониторинга погоды с использованием WeatherAPI.com.

---

## 📋 Описание

Weather Monitor — это веб-приложение, которое позволяет получать прогноз погоды по географическим координатам.  
Данные предоставляются сервисом [WeatherAPI.com](https://www.weatherapi.com/).

Приложение отображает:
- Текущую погоду
- Почасовой прогноз (на сегодня и завтра)
- Прогноз на 3 дня

---

## ✨ Особенности

- 🌤 Текущая погода с иконками и описанием
- 📊 Почасовой прогноз на текущий день и завтра
- 📅 Прогноз на 3 дня
- 🌡 Отображение температуры, влажности, скорости ветра и видимости
- 📱 Адаптивный дизайн
- ✅ Валидация входных данных
- 🛡 Обработка ошибок API
- 🐳 **Поддержка Docker** (PHP-FPM, Nginx, PostgreSQL, Redis, Memcached)

---

## 🛠 Технологии

- **PHP 8.5+**
- **Composer**
- **cURL**
- **Valitron** — валидация
- **PHP dotenv** — переменные окружения
- **Bootstrap 5** + **Bootstrap Icons**
- **Docker** + **Docker Compose**
- **PostgreSQL**, **Redis**, **Memcached** (опционально)

---

## 🐳 Установка и запуск через Docker

### 1. Клонирование репозитория

```bash
git clone https://github.com/Alexander-Yurtaev/WeatherMonitor.PHP.git
cd WeatherMonitor.PHP
```

### 2. Настройка переменных окружения

Создайте файл `.env` в корне проекта:

```bash
cp .env.example .env
```

Отредактируйте `.env` и укажите ваш API-ключ:

```
WEATHER_API_KEY=your_api_key_here
```

### 3. Запуск контейнеров

```bash
docker-compose up -d
```

### 4. Установка пакетов и зависимостей

```bash
docker compose exec fpm composer install
```

После запуска приложение будет доступно по адресу:  
👉 [http://localhost:8080](http://localhost:8080)

### 5. Остановка контейнеров

```bash
docker-compose down
```

---

## 🚀 Использование

1. Введите **широту** (например, `55.7558`) и **долготу** (например, `37.6173`)
2. Нажмите **«Показать прогноз»**
3. Просмотрите актуальную погоду и прогноз

---

## 📁 Структура проекта (ключевые каталоги)

```
WeatherMonitor.PHP/
├── containers/
│   ├── fpm/                # Dockerfile для PHP-FPM
│   ├── nginx/              # Конфиг Nginx
│   └── postgres/           # Инициализация БД (опционально)
├── src/
│   ├── exceptions/         # Классы исключений
│   ├── helpers/            # Downloader, Utils
│   ├── incs/               # header.tpl.php, footer.tpl.php
│   ├── index.php           # Главная страница (форма ввода)
│   └── weather.php         # Страница с прогнозом
├── .env.example            # Пример переменных окружения
├── docker-compose.yaml     # Конфигурация Docker
├── composer.json           # Зависимости Composer
└── README.md
```

---

## 🔧 Основные классы

### `Downloader` (`WeatherMonitor\helpers\Downloader`)

Выполняет HTTP-запросы через cURL.

```php
$downloader = new \WeatherMonitor\helpers\Downloader();
$data = $downloader->load($url);
```

### `Utils` (`WeatherMonitor\helpers\Utils`)

Статические вспомогательные методы.

| Метод | Описание |
|-------|----------|
| `GetTimeFromDate()` | Извлекает время из даты |
| `GetNumber()` | Округляет число или возвращает `--` |
| `GetImageTag()` | Генерирует HTML-тег `<img>` для иконки |
| `ConvertKmH2Ms()` | Конвертирует км/ч в м/с |
| `GetValueFromEnv()` | Получает значение из `.env` |
| `GetHour()` | Извлекает час из даты |
| `GetWeekdayName()` | Возвращает название дня недели на русском |

---

## 🐛 Обработка ошибок

Приложение корректно обрабатывает:
- Отсутствие API-ключа
- Ошибки подключения к WeatherAPI
- Неверный формат координат
- Отсутствие данных по координатам

---

## 📝 Лицензия

MIT

---

## 👨‍💻 Автор

**Alexander Yurtaev**  
📧 ayurtaev@mail.ru

---

## 🙏 Благодарности

- [WeatherAPI.com](https://www.weatherapi.com/)
- [Valitron](https://github.com/vlucas/valitron)
- [PHP dotenv](https://github.com/vlucas/phpdotenv)

---

## 📸 Скриншоты

*Настройки*

![Настройки](./documents/screenshorts/settings.png)

*Главное окно*

![Главное окно](./documents/screenshorts/main.png)