<?php

declare(strict_types=1);

use Valitron\Validator;

require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = ['lat' => $_POST['lat'], 'lon' => $_POST['lon']];
    $v = new Validator($data);
    $v->rules([
            'required' => ['lat', 'lon'],
            'numeric' => ['lat', 'lon']
    ]);
    if ($v->validate()){
        header("Location: weather.php?lat=" . $_POST['lat'] . "&lon=" . $_POST['lon']);
        die;

    }
    else
    {
        $errors = $v->errors();
        $latError = isset($errors['lat']);
        $lonError = isset($errors['lon']);
    }
}

include_once __DIR__ . '/incs/header.tpl.php';
?>

    <div class="app-card" id="app">
        <form method="post">
            <div class="screen settings-screen active" id="settingsScreen">
                <div class="settings-title">🌤️ Погода</div>
                <div class="settings-sub">Введите координаты для прогноза</div>

                <div class="form-group">
                    <label for="latInput">Широта (LAT)</label>
                    <input type="text" class="form-control <?= isset($latError) && $latError === true ? 'is-invalid' : '' ?>" id="latInput" name="lat" placeholder="Например: 55.7558"
                           value="<?= isset($data) ? $data['lat'] : '' ?>">
                    <div class="invalid-feedback <?= isset($latError) && $latError === true ? 'show' : '' ?>" id="latError" >Введите корректное число с плавающей точкой</div>
                </div>

                <div class="form-group">
                    <label for="lonInput">Долгота (LON)</label>
                    <input type="text" class="form-control <?= isset($lonError) && $lonError === true ? 'is-invalid' : '' ?>" id="lonInput" name="lon" placeholder="Например: 37.6173"
                           value="<?= isset($data) ? $data['lon'] : '' ?>">
                    <div class="invalid-feedback <?= isset($lonError) && $lonError === true ? 'show' : '' ?>" id="lonError">Введите корректное число с плавающей точкой</div>
                </div>

                <button class="btn-primary-custom" id="showWeatherBtn">
                    <i class="bi bi-arrow-right-circle"></i> Показать прогноз
                </button>
            </div>
        </form>

<?php
include_once __DIR__ . '/incs/footer.tpl.php';