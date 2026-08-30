FROM php:8.5-fpm

# Меняем репозитории на зеркала Яндекса
RUN rm -f /etc/apt/sources.list.d/* && \
    echo 'deb http://mirror.yandex.ru/debian bookworm main contrib non-free non-free-firmware\n\
    deb-src http://mirror.yandex.ru/debian bookworm main contrib non-free non-free-firmware\n\
    \n\
    deb http://security.debian.org/debian-security bookworm-security main contrib non-free non-free-firmware\n\
    deb-src http://security.debian.org/debian-security bookworm-security main contrib non-free non-free-firmware\n\
    \n\
    deb http://mirror.yandex.ru/debian bookworm-updates main contrib non-free non-free-firmware\n\
    deb-src http://mirror.yandex.ru/debian bookworm-updates main contrib non-free non-free-firmware' > /etc/apt/sources.list

# Самое важное: ставим unzip (чтобы Composer мог распаковывать архивы)
# zip (архиватор) тут не нужен, нужен именно unzip (распаковщик)
RUN apt-get update && apt-get install -y --no-install-recommends unzip && rm -rf /var/lib/apt/lists/*

# Расширение zip в образе php:8.3-fpm уже включено по умолчанию — ничего включать не надо

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
