<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Инструкция по настройке - Screenshot uploader</title>
    <link rel="stylesheet" href="/screenshots-uploader/assets/style.css" />
</head>
<body class="instruction-page">

<header>
    <div class="logo-container">
        <a href="/screenshots-uploader/" class="logo-link">
            <div class="logo">Screenshot uploader</div>
        </a>
        <a href="/screenshots-uploader/" class="nav-link instruction-link">
            <svg height="18" viewBox="0 0 16 16" width="18" class="nav-icon"><path fill="currentColor" d="M9.78 12.53a.75.75 0 0 1-1.06 0L4.47 8.28a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 1 1 1.06 1.06L6.81 7h5.44a.75.75 0 0 1 0 1.5H6.81l2.97 2.97a.75.75 0 0 1 0 1.06Z"></path></svg>
            <span>На главную</span>
        </a>
        <a href="https://github.com/igoryakus/screenshots-uploader" class="nav-link github-link">
            <svg height="20" viewBox="0 0 16 16" width="20" class="github-logo"><path fill="currentColor" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg>
            <span>Github</span>
        </a>
    </div>
</header>

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

</body>
</html>
