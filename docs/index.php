<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Инструкция по настройке - Screenshot uploader</title>
    <link rel="stylesheet" href="/screenshots-uploader/assets/style.css" />
</head>
<body class="instruction-page">

<?php 
$is_home = false; // Для docs/index.php это не главная в контексте шапки
$image_url = ""; // В инструкции нет конкретной картинки для скачивания
include dirname(__DIR__) . '/header.php'; 
?>

<div class="main-content scrollable">
    <div class="container">
        <h1>Инструкция по настройке сервиса</h1>
        
        <div class="step">
            <h3>Шаг 1: Подготовка VPS</h3>
            <p>Вам понадобится собственный VPS. Убедитесь, что у вас есть SSH-доступ с правами root или sudo.</p>
        </div>

        <div class="step">
            <h3>Шаг 2: Настройка SSH-доступа по ключу</h3>
            <p>Для безопасной и автоматической выгрузки скриншотов необходим доступ по SSH-ключу.</p>
            
            <h4>Создание ключа:</h4>
            <ul>
                <li><strong>macOS / Linux:</strong> Откройте терминал и введите <code>ssh-keygen -t ed25519</code>. Нажимайте Enter до завершения.</li>
                <li><strong>Windows:</strong> Откройте PowerShell или командную строку и также введите <code>ssh-keygen -t ed25519</code>.</li>
            </ul>

            <h4>Установка ключа на сервер:</h4>
            <p>Выполните команду на вашем компьютере (заменив user и ip):</p>
            <pre><code>ssh-copy-id -i ~/.ssh/id_ed25519.pub user@your_server_ip</code></pre>
            <p>Если <code>ssh-copy-id</code> не доступен, вручную скопируйте содержимое вашего <code>.pub</code> файла в файл <code>~/.ssh/authorized_keys</code> на сервере.</p>
        </div>

        <div class="step">
            <h3>Шаг 3: Установка Nginx, PHP и Git</h3>
            <p>Выполните команды на сервере:</p>
            <pre><code>sudo apt update
sudo apt install nginx php-fpm git</code></pre>
        </div>

        <div class="step">
            <h3>Шаг 4: Клонирование проекта</h3>
            <p>Перейдите в директорию веб-сервера и склонируйте проект:</p>
            <pre><code>cd /var/www/html
sudo git clone https://github.com/igoryakus/screenshots-uploader.git
sudo chown -R www-data:www-data /var/www/html/screenshots-uploader
sudo chmod -R 755 /var/www/html/screenshots-uploader/files</code></pre>
        </div>

        <div class="step">
            <h3>Шаг 5: Настройка Nginx</h3>
            <p>Отредактируйте конфигурацию сайта (обычно <code>/etc/nginx/sites-available/default</code>):</p>
            <pre><code>location /screenshots-uploader/ {
    try_files $uri $uri/ /screenshots-uploader/index.php?$args;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock; # Проверьте вашу версию PHP
    }
}</code></pre>
            <p>После изменений перезапустите Nginx: <code>sudo systemctl restart nginx</code></p>
        </div>

        <div class="step">
            <h3>Шаг 6: Настройка клиента (macOS)</h3>
            <p>1. Скачайте скрипт загрузчика: <a href="/screenshots-uploader/bash-client/screenshots_uploader.sh" download class="text-link">screenshots_uploader.sh</a> и положите этот файл в надежное место.</p>
            <p>2. Отредактируйте в нем переменную <code>WATCH_DIR</code> (папка со скриншотами), а также <code>REMOTE_HOST</code>, <code>REMOTE_USER</code> и <code>SSH_KEY</code>.</p>
            <p>3. Установите <code>fswatch</code>: <code>brew install fswatch</code></p>
            <p>4. Сделайте скрипт исполняемым: <code>chmod +x /path/to/screenshots_uploader.sh</code></p>
            
            <p>5. Для автозапуска скачайте файл <a href="/screenshots-uploader/bash-client/com.user.screenshots-uploader.plist" download class="text-link">com.user.screenshots-uploader.plist</a> и положите его в <code>~/Library/LaunchAgents/</code>.</p>
            <p>Запустите: <code>launchctl load ~/Library/LaunchAgents/com.user.screenshot-uploader.plist</code></p>
        </div>

        <div class="step">
            <h3>Шаг 7: Настройка клиента (Windows)</h3>
            <p>1. Скачайте PowerShell-скрипт: <a href="/screenshots-uploader/bash-client/screenshots_uploader.ps1" download class="text-link">screenshots_uploader.ps1</a>.</p>
            <p>2. Откройте его в текстовом редакторе и настройте переменные в начале файла (пути к папкам, данные сервера).</p>
            <p>3. Для запуска откройте PowerShell и выполните скрипт. Чтобы он работал в фоне, можно создать ярлык в папке «Автозагрузка» (<code>Win+R</code> -> <code>shell:startup</code>) с командой:</p>
            <pre><code>powershell.exe -WindowStyle Hidden -File "C:\путь\к\screenshots_uploader.ps1"</code></pre>
            <p><em>Примечание: Убедитесь, что у вас разрешен запуск скриптов. Проверить текущую политику можно командой:</em></p>
            <pre><code>Get-ExecutionPolicy -List</code></pre>
            <p><em>Если напротив <code>CurrentUser</code> или <code>LocalMachine</code> не указано <code>RemoteSigned</code> или <code>Unrestricted</code>, разрешите запуск:</em></p>
            <pre><code>Set-ExecutionPolicy RemoteSigned -Scope CurrentUser</code></pre>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/footer.php'; ?>
