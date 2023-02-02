<h1 align="center">TrydeX</h1>

<p float="center">
  <img src="https://user-images.githubusercontent.com/44779327/216434075-e5df7132-ff2a-492a-aefb-9044753ad355.png" width="400">
  <img src="https://user-images.githubusercontent.com/44779327/216434183-86ee68fd-7b08-44fc-b67c-135d6fa355da.png" width="400">
</p>

![image](https://user-images.githubusercontent.com/44779327/216434792-cb5bf3c5-5645-4c1f-90ef-bbdca8df001d.png)


<p align="center">Самая быстрая и конфиденциальная мета-поисковая система</p>

<br>

### Немного о TrydeX

Trydex использует популярные поисковые системы.

### Trydex поддерживает поисковые системы

| Поиск |  Форумы | Видео | Картинки | API |
|-|-|-|-|-|
| Yandex | CrowdView | Brave | ❌ | ✅ |
| Google |  |  |  |  |


### Особенности

- Без рекламы
- Без JavaScript
- Специальные запросы
- Быстрый поиск (например: !yt linux)
- Удаляются фрагменты отслеживания с URL-адресов
- Несколько цветовых тем
- Поддерживает POST и GET запросы
- Популярные сайты социальных сетей (YouTube, Instagram и т.д.) заменяются аналогами, дружественными к конфиденциальности.
- Простой в использовании JSON API для разработчиков
- Никакие сторонние библиотеки / фреймворки не используются
- Простота размещения

## Размещение сайта TrydeX

Эти инструкции предназначены для Debian GNU/Linux, но они должны быть одинаковыми во всех дистрибутивах GNU/Linux и системах *BSD.

<br>

Install the packages

```bash
sudo apt install php php-fpm php-dom php-curl nginx git
```

Clone TrydeX

```bash
git clone https://github.com/lencodigitexer/trydex.git
```

Rename the config and opensearch file

```bash
cd trydex
mv config.php.example config.php
mv opensearch.xml.example opensearch.xml
```

Change opensearch.xml to point to your domain

```bash
sed -i 's/http:\/\/localhost/https:\/\/your.domain/g' opensearch.xml
```

Example nginx config

```nginx
server {
        listen 80;

        server_name your.domain;

        root /var/www/html/librex;
        index index.php;

        location ~ \.php$ {
               include snippets/fastcgi-php.conf;
               fastcgi_pass unix:/run/php/php-fpm.sock;
        }
}
```

Start the php-fpm and the nginx systemd service

```bash
sudo systemctl enable --now php-fpm nginx
```

Теперь TrydeX должен быть запущен!

