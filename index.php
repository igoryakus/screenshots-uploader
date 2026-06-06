<?php
// Получаем текущий URL и вытаскиваем из него имя запрашиваемого файла
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Отрезаем базовый путь /screenshots-uploader/ если он есть
$base_path = '/screenshots-uploader/';
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

// Если файл не найден и это не корень, отдаем ошибку 404
if (!$is_home && !file_exists($image_path)) {
	header("HTTP/1.0 404 Not Found");
	echo "<h1>Изображение не найдено</h1>";
	exit;
}

// URL самой картинки для отображения (если это не главная)
$image_url = $is_home ? "" : "/screenshots-uploader/files/" . $image_file;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $is_home ? "Screenshot uploader - Быстрый обмен скриншотами" : "Просмотр скриншота " . htmlspecialchars($clean_name); ?></title>

	<!-- Подключаем стили плагина Viewer.js -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.7/viewer.min.css" />

	<!-- Основные стили страницы -->
	<link rel="stylesheet" href="/screenshots-uploader/assets/style.css" />
</head>
<body class="<?php echo $is_home ? 'home-page scrollable' : ''; ?>">

<header>
	<div class="logo-container">
		<a href="/screenshots-uploader/" class="logo-link">
			<div class="logo">Screenshots uploader</div>
		</a>
        <a href="/screenshots-uploader/docs/" class="nav-link instruction-link">
			<svg height="18" viewBox="0 0 16 16" width="18" class="nav-icon"><path fill="currentColor" d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"></path><path fill="currentColor" d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 4v-.5a.5.5 0 0 1 1 0V9h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 4v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zM4 3h8v1H4V3zm0 4h8v1H4V7zm0 4h8v1H4v-1z"></path></svg>
			<span>Инструкция</span>
		</a>
    <a href="https://github.com/igoryakus/screenshots-uploader" class="nav-link github-link">
            <svg height="20" viewBox="0 0 16 16" width="20" class="github-logo"><path fill="currentColor" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg>
            <span>GitHub</span>
        </a>
	</div>
	<?php if (!$is_home): ?>
		<a href="<?php echo $image_url; ?>" download class="btn-download">Скачать оригинал</a>
	<?php endif; ?>
</header>
<div class="main-content">
	<?php if ($is_home): ?>
		<div class="container landing-content">
			<h1>Максимально быстрая загрузка скриншотов</h1>
			<p class="lead">Создавайте, загружайте и делитесь скриншотами мгновенно и без навязчивой рекламы.</p>

            <h2>Возможности</h2>
            <div class="features">
                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                        </div>
                        <h3>Мгновенная загрузка</h3>
                    </div>
                    <p>Отправляйте скриншоты на сервер в тот же момент, когда файлы записываются на диск.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                        </div>
                        <h3>Автокопирование URL</h3>
                    </div>
                    <p>Готовая к отправке ссылка генерируется сразу и вы можете ей пользоваться, пока в фоне файл копируется на сервер.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <h3>Максимальная скорость</h3>
                    </div>
                    <p>Мгновенная прямая загрузка на VPS без задержек публичных хостингов.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                        <h3>Приватность на своем сервере</h3>
                    </div>
                    <p>Ваши изображения остаются в безопасности на вашем собственном VPS под вашим полным контролем.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path></svg>
                        </div>
                        <h3>Отсутствие рекламы</h3>
                    </div>
                    <p>Полное отсутствие баннеров, всплывающих окон, вотермарок и капч.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12c-2-2.67-4-4-6-4a4 4 0 1 0 0 8c2 0 4-1.33 6-4zm0 0c2 2.67 4 4 6 4a4 4 0 0 0 0-8c-2 0-4 1.33-6 4z"></path></svg>
                        </div>
                        <h3>Вечное хранение</h3>
                    </div>
                    <p>Ваши файлы размещены навсегда и никогда не удалятся автоматически.</p>
                </div>
            </div>

            <div class="demo-image-container">
                <img src="/screenshots-uploader/assets/img/demo-file.png" alt="Демонстрация работы" class="demo-image">
            </div>

			<div class="cta-section">
				<a href="/screenshots-uploader/docs/" class="btn-download">Как настроить?</a>
			</div>
		</div>
	<?php else: ?>
		<!-- Картинка, обернутая в блок для инициализации плагина -->
		<div id="image-gallery">
			<img id="screenshot" src="<?php echo $image_url; ?>" alt="Скриншот" style="display: none;">
		</div>
	<?php endif; ?>
</div>

<div class="footer">
    <div class="container">
        <p>&copy; <?=date('Y'); ?> Screenshots uploader. Yakus Corporation. Все права незащищены.</p>
    </div>
</div>

<!-- Подключаем скрипт плагина Viewer.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.7/viewer.min.js"></script>

<!-- Инициализация плагина -->
<script src="/screenshots-uploader/assets/script.js"></script>
</body>
</html>