<?php
$page_title = "Инструкция по настройке ShotHost";
$page_description = "Как развернуть ShotHost на своём VPS и настроить загрузку скриншотов с macOS и Windows.";
$body_class = "docs-page";
$current_page = "docs";
include dirname(__DIR__) . '/head.php';
include dirname(__DIR__) . '/header.php';
?>

<main>
	<section class="product hero">
		<div class="wrap">
			<h1 class="product-title">Инструкция по настройке</h1>
			<p class="product-tagline">Свой сервер, SSH-ключ и клиент на компьютере. Семь шагов, и скриншоты летят на ваш VPS.</p>
			<ol class="steps">
				<li class="step">
					<span class="principle-num mono">01</span>
					<h2>Подготовка VPS</h2>
					<p>Вам понадобится собственный VPS. Убедитесь, что у вас есть SSH-доступ с правами root или sudo.</p>
				</li>
				<li class="step">
					<span class="principle-num mono">02</span>
					<h2>Настройка SSH-доступа по ключу</h2>
					<p>Для безопасной и автоматической выгрузки скриншотов необходим доступ по SSH-ключу.</p>

					<h3>Создание ключа:</h3>
					<ul>
						<li><strong>macOS / Linux:</strong> Откройте терминал и введите <code>ssh-keygen -t ed25519</code>. Нажимайте Enter до завершения.</li>
						<li><strong>Windows:</strong> Откройте PowerShell или командную строку и также введите <code>ssh-keygen -t ed25519</code>.</li>
					</ul>

					<h3>Установка ключа на сервер:</h3>
					<p>Выполните команду на вашем компьютере (заменив user и ip):</p>
					<pre><code>ssh-copy-id -i ~/.ssh/id_ed25519.pub user@your_server_ip</code></pre>
					<p>Если <code>ssh-copy-id</code> не доступен, вручную скопируйте содержимое вашего <code>.pub</code> файла в файл <code>~/.ssh/authorized_keys</code> на сервере.</p>
				</li>
				<li class="step">
					<span class="principle-num mono">03</span>
					<h2>Установка Nginx, PHP и Git</h2>
					<p>Выполните команды на сервере:</p>
					<pre><code>sudo apt update
sudo apt install nginx php-fpm git</code></pre>
				</li>
				<li class="step">
					<span class="principle-num mono">04</span>
					<h2>Клонирование проекта</h2>
					<p>Перейдите в директорию веб-сервера и склонируйте проект:</p>
					<pre><code>cd /var/www/html
sudo git clone https://github.com/igoryakus/shothost.git
sudo chown -R www-data:www-data /var/www/html/shothost
sudo chmod -R 755 /var/www/html/shothost/files</code></pre>
				</li>
				<li class="step">
					<span class="principle-num mono">05</span>
					<h2>Настройка Nginx</h2>
					<p>Отредактируйте конфигурацию сайта (обычно <code>/etc/nginx/sites-available/default</code>):</p>
					<pre><code>location /shothost/ {
    try_files $uri $uri/ /shothost/index.php?$args;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock; # Проверьте вашу версию PHP
    }
}</code></pre>
					<p>После изменений перезапустите Nginx: <code>sudo systemctl restart nginx</code></p>
				</li>
				<li class="step">
					<span class="principle-num mono">06</span>
					<h2>Настройка клиента (macOS)</h2>
					<p>1. Скачайте приложение ShotHost: откройте <a href="https://github.com/igoryakus/shothost/actions/workflows/macos-app.yml" class="text-link">последнюю сборку «macOS app»</a> и в блоке Artifacts скачайте <code>ShotHost</code>. Можно собрать его и самостоятельно: <code>./macos-app/build-app.sh</code> (нужны Xcode Command Line Tools).</p>
					<p>2. Распакуйте архив и перенесите <code>ShotHost.app</code> в «Программы». В первый раз откройте его через правый клик → «Открыть»: приложение подписано без сертификата Apple.</p>
					<p>3. При первом запуске откроется окно настроек. Укажите папку со скриншотами, сервер, SSH-порт, пользователя, папку на сервере, SSH-ключ и адрес ShotHost, затем нажмите «Проверить соединение» и «Сохранить».</p>
					<p>4. Чтобы приложение работало постоянно, включите в настройках «Запускать при входе в систему».</p>
				</li>
				<li class="step">
					<span class="principle-num mono">07</span>
					<h2>Настройка клиента (Windows)</h2>
					<p>1. Скачайте PowerShell-скрипт: <a href="/shothost/bash-client/shothost.ps1" download class="text-link">shothost.ps1</a>.</p>
					<p>2. Откройте его в текстовом редакторе и настройте переменные в начале файла (пути к папкам, данные сервера).</p>
					<p>3. Для запуска откройте PowerShell и выполните скрипт. Чтобы он работал в фоне, можно создать ярлык в папке «Автозагрузка» (<code>Win+R</code> → <code>shell:startup</code>) с командой:</p>
					<pre><code>powershell.exe -WindowStyle Hidden -File "C:\путь\к\shothost.ps1"</code></pre>
					<p><em>Примечание: Убедитесь, что у вас разрешен запуск скриптов. Проверить текущую политику можно командой:</em></p>
					<pre><code>Get-ExecutionPolicy -List</code></pre>
					<p><em>Если напротив <code>CurrentUser</code> или <code>LocalMachine</code> не указано <code>RemoteSigned</code> или <code>Unrestricted</code>, разрешите запуск:</em></p>
					<pre><code>Set-ExecutionPolicy RemoteSigned -Scope CurrentUser</code></pre>
				</li>
			</ol>
		</div>
	</section>
</main>

<?php include dirname(__DIR__) . '/footer.php'; ?>
