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
$current_page = $is_home ? "home" : "";
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
				<div class="chips">
					<span class="chip">macOS</span>
					<span class="chip">Windows</span>
					<span class="chip">Свой VPS</span>
					<span class="chip">Открытый код</span>
				</div>
				<div class="hero-actions">
					<a class="btn btn-primary" href="/shothost/docs/">Как настроить</a>
					<a class="btn btn-ghost" href="https://github.com/igoryakus/shothost">Код на GitHub →</a>
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
				<li><b>Без рекламы</b>Никаких баннеров, всплывающих окон, водяных знаков и капч.</li>
				<li><b>Вечное хранение</b>Файлы хранятся, пока вы сами их не удалите, ничего не пропадает автоматически.</li>
			</ul>
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
