<?php
// Получаем текущий URL и вытаскиваем из него имя запрашиваемого файла
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Отрезаем базовый путь /shothost/ если он есть
$base_path = '/shothost/';
$relative_path = $request_uri;

if (strpos($request_uri, $base_path) === 0) {
    $relative_path = substr($request_uri, strlen($base_path));
}

$clean_name = trim($relative_path, '/');
// Если после очистки осталось index.php, то это тоже главная
if ($clean_name === 'index.php') {
    $clean_name = '';
}

// Ищем файл с расширением .png
$image_file = $clean_name . '.png';
$image_path = __DIR__ . '/files/' . $image_file;

// Определяем, показываем мы картинку или главную страницу
$is_home = empty($clean_name);

// Проверяем наличие файла
$file_exists = !empty($clean_name) && file_exists($image_path);

// URL самой картинки для отображения (если это не главная)
$image_url = $is_home ? "" : "/shothost/files/" . rawurlencode($image_file);

// Параметры для общих head.php, header.php и footer.php
$page_title = $is_home ? "ShotHost: мгновенная загрузка скриншотов на свой сервер" : "Скриншот " . $clean_name;
$page_description = $is_home ? "ShotHost загружает скриншоты на ваш VPS в момент их создания и сразу кладёт ссылку в буфер обмена. Без рекламы и публичных хостингов, открытый код." : "";
$body_class = $is_home ? "home-page" : "viewer-page";
$use_viewer = !$is_home;
$download_url = $file_exists ? $image_url : "";

include __DIR__ . '/head.php';
include __DIR__ . '/header.php';
?>

<?php if ($is_home): ?>
<main>
	<section class="product hero" id="about">
		<div class="wrap product-grid">
			<div>
				<svg class="tile tile-lg" aria-hidden="true"><use href="#i-sc"/></svg>
				<h1 class="product-title">ShotHost</h1>
				<p class="product-tagline">Максимально быстрая загрузка скриншотов.</p>
				<p class="product-text">Делаете скриншот, и ссылка на него сразу оказывается в буфере обмена. Файл в это время уходит в фоне на ваш собственный сервер, без публичных хостингов, рекламы и капч.</p>
				<p class="product-text">На Mac это нативное приложение в строке меню: все настройки в одном окне, пауза, ручная загрузка файла, последние ссылки и уведомление с кнопкой «Открыть». Для Windows есть скрипт PowerShell.</p>
				<div class="chips">
					<span class="chip">Приложение для macOS</span>
					<span class="chip">Windows</span>
					<span class="chip">Свой VPS</span>
					<span class="chip">Открытый код</span>
				</div>
				<div class="hero-actions">
					<a class="btn btn-primary" href="#download">Скачать</a>
					<a class="btn btn-ghost" href="#setup">Как настроить</a>
				</div>
			</div>
			<!-- Как это выглядит: скриншот, загрузка в фоне и ссылка, которая уже в буфере обмена -->
			<div class="demo" aria-label="Как работает ShotHost">
				<div class="demo-bar" aria-hidden="true"><i></i><i></i><i></i><span class="mono">ShotHost</span></div>
				<div class="demo-row">
					<span class="demo-step mono">01</span>
					<div class="demo-body">
						<b>Скриншот сохранён</b>
						<span class="demo-file mono">SCR-20261011-jvpl.png</span>
					</div>
					<span class="demo-ok" aria-hidden="true">✓</span>
				</div>
				<div class="demo-row">
					<span class="demo-step mono">02</span>
					<div class="demo-body">
						<b>Ссылка в буфере обмена</b>
						<span class="demo-link mono">yakus.ru/shothost/SCR-20261011-jvpl_2af3a7d3</span>
					</div>
					<span class="demo-ok" aria-hidden="true">✓</span>
				</div>
				<div class="demo-row">
					<span class="demo-step mono">03</span>
					<div class="demo-body">
						<b>Загрузка на ваш сервер</b>
						<span class="demo-progress" aria-hidden="true"><span></span></span>
					</div>
					<span class="demo-ok demo-ok-late" aria-hidden="true">✓</span>
				</div>
			</div>
		</div>
	</section>

	<section class="product" id="features">
		<div class="wrap">
			<h2 class="section-title">Возможности</h2>
			<ul class="features features-grid">
				<li><b>Мгновенная загрузка</b>Скриншот отправляется на сервер в тот же момент, когда файл записывается на диск.</li>
				<li><b>Ссылка сразу</b>Готовая ссылка появляется в буфере обмена ещё до окончания копирования, ей можно делиться сразу.</li>
				<li><b>Максимальная скорость</b>Прямая загрузка на ваш VPS без очередей и задержек публичных хостингов.</li>
				<li><b>Ваш сервер</b>Изображения лежат на вашем собственном VPS под вашим полным контролем.</li>
				<li><b>Приложение в строке меню</b>Нативное приложение для macOS: настройка в одном окне, проверка соединения, автозапуск при входе в систему.</li>
				<li><b>Без рекламы</b>Никаких баннеров, всплывающих окон, водяных знаков и капч.</li>
				<li><b>Вечное хранение</b>Файлы хранятся, пока вы сами их не удалите, ничего не пропадает автоматически.</li>
				<li><b>Открытый код</b>Весь код на GitHub под лицензией MIT: сервер на PHP, приложение на Swift.</li>
			</ul>
		</div>
	</section>

	<section class="product" id="download">
		<div class="wrap">
			<h2 class="section-title">Скачать ShotHost</h2>
			<p class="product-text">Ссылки ведут на последний выпуск в GitHub Releases: его собирает GitHub Actions при каждой новой версии.</p>
			<div class="downloads">
				<a class="dl" href="https://github.com/igoryakus/shothost/releases/latest/download/ShotHost.zip">
					<span class="dl-os"><svg class="os-icon" aria-hidden="true"><use href="#i-apple"/></svg>macOS</span>
					<span class="dl-file mono">ShotHost.zip</span>
					<span class="dl-note">Приложение для строки меню, macOS 13 и новее, Apple Silicon и Intel. Распакуйте и перенесите в «Программы». В первый раз откройте через правый клик → «Открыть»: приложение подписано без сертификата Apple.</span>
					<span class="btn btn-primary">Скачать</span>
				</a>
				<a class="dl" href="https://github.com/igoryakus/shothost/releases/latest/download/shothost.ps1">
					<span class="dl-os"><svg class="os-icon" aria-hidden="true"><use href="#i-windows"/></svg>Windows</span>
					<span class="dl-file mono">shothost.ps1</span>
					<span class="dl-note">Скрипт PowerShell для Windows 10 и 11. Настройки задаются в начале файла, автозапуск через папку «Автозагрузка», подробности в инструкции ниже.</span>
					<span class="btn btn-primary">Скачать</span>
				</a>
				<a class="dl" href="https://github.com/igoryakus/shothost">
					<span class="dl-os"><svg class="os-icon" aria-hidden="true"><use href="#i-github"/></svg>Сервер</span>
					<span class="dl-file mono">igoryakus/shothost</span>
					<span class="dl-note">Страница просмотра на PHP для вашего VPS, ставится через git clone. Там же исходники приложения и лицензия MIT.</span>
					<span class="btn btn-ghost">Открыть на GitHub →</span>
				</a>
			</div>
		</div>
	</section>

	<section class="product" id="setup">
		<div class="wrap">
			<h2 class="section-title">Инструкция по настройке</h2>
			<p class="product-text">Свой сервер, SSH-ключ и клиент на компьютере. Семь шагов, и скриншоты летят на ваш VPS.</p>
			<ol class="steps">
				<li class="step">
					<span class="principle-num mono">01</span>
					<h3 class="step-title">Подготовка VPS</h3>
					<p>Вам понадобится собственный VPS. Убедитесь, что у вас есть SSH-доступ с правами root или sudo.</p>
				</li>
				<li class="step">
					<span class="principle-num mono">02</span>
					<h3 class="step-title">Настройка SSH-доступа по ключу</h3>
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
				</li>
				<li class="step">
					<span class="principle-num mono">03</span>
					<h3 class="step-title">Установка Nginx, PHP и Git</h3>
					<p>Выполните команды на сервере:</p>
					<pre><code>sudo apt update
sudo apt install nginx php-fpm git</code></pre>
				</li>
				<li class="step">
					<span class="principle-num mono">04</span>
					<h3 class="step-title">Клонирование проекта</h3>
					<p>Перейдите в директорию веб-сервера и склонируйте проект:</p>
					<pre><code>cd /var/www/html
sudo git clone https://github.com/igoryakus/shothost.git
sudo chown -R www-data:www-data /var/www/html/shothost
sudo chmod -R 755 /var/www/html/shothost/files</code></pre>
				</li>
				<li class="step">
					<span class="principle-num mono">05</span>
					<h3 class="step-title">Настройка Nginx</h3>
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
					<h3 class="step-title">Настройка клиента (macOS)</h3>
					<p>1. Скачайте <a href="#download" class="text-link">ShotHost для macOS</a> из блока «Скачать» выше. Можно собрать приложение и самостоятельно: <code>./macos-app/build-app.sh</code> (нужны Xcode Command Line Tools).</p>
					<p>2. Распакуйте архив и перенесите <code>ShotHost.app</code> в «Программы». В первый раз откройте его через правый клик → «Открыть»: приложение подписано без сертификата Apple.</p>
					<p>3. При первом запуске откроется окно настроек. Укажите папку со скриншотами, сервер, SSH-порт, пользователя, папку на сервере, SSH-ключ и адрес ShotHost, затем нажмите «Проверить соединение» и «Сохранить».</p>
					<p>4. Чтобы приложение работало постоянно, включите в настройках «Запускать при входе в систему».</p>
				</li>
				<li class="step">
					<span class="principle-num mono">07</span>
					<h3 class="step-title">Настройка клиента (Windows)</h3>
					<p>1. Скачайте <a href="#download" class="text-link">скрипт для Windows</a> <code>shothost.ps1</code> из блока «Скачать» выше.</p>
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
<?php elseif ($file_exists): ?>
	<!-- Картинка найдена: показываем её в Viewer.js -->
	<div id="image-gallery">
		<img id="screenshot" src="<?php echo htmlspecialchars($image_url); ?>" alt="Скриншот" style="display: none;">
	</div>
<?php else: ?>
	<!-- Файл еще не докачался: показываем лоадер -->
	<div class="loader-container" id="waiting-loader" data-url="<?php echo htmlspecialchars($image_url); ?>">
		<div class="spinner" aria-hidden="true"></div>
		<h2>Файл ещё загружается…</h2>
		<p>Страница обновится сама, как только скриншот появится на сервере.</p>
	</div>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
