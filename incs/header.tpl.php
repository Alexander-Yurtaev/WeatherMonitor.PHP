<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Погода</title>

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(145deg, #0b1a2e 0%, #1b3a5e 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            padding: 16px;
            color: #f0f7ff;
        }

        .app-card {
            max-width: 500px;
            width: 100%;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 48px;
            padding: 24px 20px 32px;
            box-shadow: 0 25px 50px -8px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.03);
            transition: all 0.2s;
            max-height: 95vh;
            display: flex;
            flex-direction: column;
        }

        /* === СКРЫТЫЕ ЭКРАНЫ === */
        .screen {
            display: none;
            flex-direction: column;
            height: 100%;
        }
        .screen.active {
            display: flex;
        }

        /* === ЭКРАН НАСТРОЕК === */
        .settings-screen {
            gap: 16px;
            padding: 8px 0;
        }
        .settings-title {
            font-weight: 600;
            font-size: 26px;
            letter-spacing: -0.3px;
            text-align: center;
            margin-bottom: 4px;
        }
        .settings-sub {
            font-size: 15px;
            color: rgba(255,255,255,0.6);
            text-align: center;
            margin-bottom: 8px;
        }

        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            font-weight: 500;
            font-size: 14px;
            color: rgba(255,255,255,0.8);
            margin-bottom: 4px;
            display: block;
        }
        .form-group .form-control {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            padding: 12px 16px;
            color: #f0f7ff;
            font-size: 16px;
            transition: 0.2s;
            width: 100%;
        }
        .form-group .form-control:focus {
            background: rgba(255,255,255,0.1);
            border-color: #6ea8fe;
            outline: none;
            box-shadow: 0 0 0 3px rgba(110, 168, 254, 0.2);
        }
        .form-group .form-control.is-invalid {
            border-color: #f87171;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.15);
        }
        .form-group .form-control.is-valid {
            border-color: #6ee7b7;
            box-shadow: 0 0 0 3px rgba(110, 231, 183, 0.15);
        }
        .form-group .invalid-feedback {
            color: #f87171;
            font-size: 13px;
            margin-top: 4px;
            display: none;
        }
        .form-group .invalid-feedback.show {
            display: block;
        }

        .btn-primary-custom {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            font-weight: 600;
            font-size: 18px;
            padding: 16px;
            border-radius: 60px;
            cursor: pointer;
            transition: 0.2s;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            margin-top: 4px;
            width: 100%;
        }
        .btn-primary-custom:hover {
            background: rgba(255,255,255,0.2);
            transform: scale(1.02);
        }
        .btn-primary-custom:active { transform: scale(0.97); }
        .btn-primary-custom:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
        }

        /* === СОСТОЯНИЯ (загрузка/ошибка) === */
        .state-overlay {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 420px;
            padding: 24px 12px;
        }
        .state-overlay.active {
            display: flex;
        }

        .spinner {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 5px solid rgba(255,255,255,0.15);
            border-top-color: #8bbaff;
            animation: spin 1s linear infinite;
            margin-bottom: 24px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .state-title {
            font-weight: 600;
            font-size: 22px;
            letter-spacing: -0.3px;
            margin-bottom: 8px;
        }
        .state-sub {
            font-size: 15px;
            color: rgba(255,255,255,0.7);
            max-width: 260px;
            margin-bottom: 28px;
        }

        .btn-retry {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            font-weight: 600;
            font-size: 16px;
            padding: 14px 36px;
            border-radius: 60px;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .btn-retry:hover {
            background: rgba(255,255,255,0.2);
            transform: scale(1.02);
        }
        .btn-retry:active { transform: scale(0.97); }

        /* === ОСНОВНОЙ КОНТЕНТ (прогноз) === */
        .weather-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }
        .weather-content.hidden {
            display: none;
        }

        .city-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 0 4px;
            flex-shrink: 0;
        }
        .city-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 20px;
            letter-spacing: -0.2px;
        }
        .city-name i {
            font-size: 26px;
            color: #9ac8ff;
        }
        .update-badge {
            font-size: 13px;
            background: rgba(255,255,255,0.08);
            padding: 6px 14px;
            border-radius: 30px;
            color: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,255,255,0.05);
        }

        .current-weather {
            background: rgba(255,255,255,0.03);
            border-radius: 40px;
            padding: 24px 20px 20px;
            margin-bottom: 16px;
            border: 1px solid rgba(255,255,255,0.06);
            box-shadow: inset 0 2px 2px rgba(255,255,255,0.02);
            flex-shrink: 0;
        }

        .current-temp-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .temp-big {
            font-weight: 600;
            font-size: 58px;
            letter-spacing: -2px;
            line-height: 1;
        }
        .temp-big sup {
            font-size: 28px;
            top: -18px;
            margin-left: 4px;
            font-weight: 400;
            color: rgba(255,255,255,0.6);
        }
        .weather-icon-big {
            font-size: 64px;
            color: #f9d976;
            text-shadow: 0 4px 20px rgba(249, 217, 118, 0.25);
        }

        .current-desc {
            font-size: 18px;
            font-weight: 500;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .current-desc .badge {
            background: rgba(255,255,255,0.04);
            padding: 4px 16px 4px 12px;
            border-radius: 40px;
            font-weight: 400;
            font-size: 15px;
            color: rgba(255,255,255,0.8);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .current-desc .badge i {
            font-size: 18px;
            color: #9ac8ff;
        }

        .extra-row {
            display: flex;
            gap: 20px;
            margin-top: 16px;
            flex-wrap: wrap;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 16px;
        }
        .extra-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            color: rgba(255,255,255,0.8);
        }
        .extra-item i {
            font-size: 20px;
            color: #7bb3ff;
        }

        .section-title {
            font-weight: 600;
            font-size: 18px;
            margin: 12px 0 8px 4px;
            letter-spacing: -0.2px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .section-title i {
            font-size: 22px;
            color: #7bb3ff;
        }

        .info-badge {
            background: rgba(255,255,255,0.05);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            color: rgba(255,255,255,0.4);
            margin-left: 8px;
        }

        .hourly-scroll {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            padding: 8px 4px 16px 4px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.15) transparent;
            -webkit-overflow-scrolling: touch;
            flex-shrink: 0;
        }
        .hourly-scroll::-webkit-scrollbar {
            height: 4px;
        }
        .hourly-scroll::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
        }

        .hour-item {
            flex: 0 0 70px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255,255,255,0.02);
            border-radius: 40px;
            padding: 12px 4px 10px;
            border: 1px solid rgba(255,255,255,0.03);
            transition: 0.2s;
            position: relative;
        }
        .hour-item .hour-label {
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,0.7);
        }
        .hour-item .hour-icon {
            font-size: 28px;
            margin: 6px 0 2px;
        }
        .hour-item .hour-temp {
            font-weight: 600;
            font-size: 18px;
        }
        .hour-item .hour-day-label {
            font-size: 9px;
            color: rgba(255,255,255,0.3);
            margin-top: 2px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* === ПРОГНОЗ НА 3 ДНЯ === */
        .day-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-top: 4px;
        }
        .day-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            background: rgba(255,255,255,0.02);
            border-radius: 60px;
            border: 1px solid rgba(255,255,255,0.03);
            transition: 0.15s;
        }
        .day-name {
            font-weight: 500;
            font-size: 16px;
            min-width: 80px;
        }
        .day-icon {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .day-icon span:first-child {
            font-size: 28px;
        }
        .day-icon .desc-text {
            font-size: 15px;
            color: rgba(255,255,255,0.7);
        }
        .day-temps {
            font-weight: 600;
            font-size: 16px;
            display: flex;
            gap: 16px;
        }
        .day-temps .max-temp {
            color: #f0f7ff;
        }
        .day-temps .min-temp {
            color: rgba(255,255,255,0.4);
        }

        .btn-back {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.6);
            padding: 8px 16px;
            border-radius: 40px;
            cursor: pointer;
            transition: 0.2s;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
            margin-top: 8px;
            align-self: flex-start;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.12);
            color: #f0f7ff;
        }

        @media (max-width: 440px) {
            .app-card { padding: 16px; }
            .temp-big { font-size: 48px; }
            .current-temp-row { flex-wrap: wrap; }
            .extra-row { gap: 12px; }
            .day-card { padding: 10px 12px; }
            .day-name { min-width: 60px; font-size: 14px; }
        }

        .text-muted { color: rgba(255,255,255,0.5); }
        .mt-1 { margin-top: 6px; }
    </style>
</head>
<body>