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

// Проверяем наличие файла
$file_exists = !empty($clean_name) && file_exists($image_path);

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

<?php include __DIR__ . '/header.php'; ?>
<div class="main-content">
	<?php if ($is_home): ?>
		<div class="container landing-content">
			<h1>Максимально быстрая загрузка скриншотов</h1>
			<p class="lead">Создавайте, загружайте и делитесь скриншотами мгновенно и без навязчивой рекламы.</p>

            <h2 id="about">Возможности</h2>
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
		<?php if ($file_exists): ?>
            <!-- Картинка найдена: инициализируем Viewer.js -->
            <div id="image-gallery">
                <img id="screenshot" src="<?php echo $image_url; ?>" alt="Скриншот" style="display: none;">
            </div>
        <?php else: ?>
            <!-- Файл еще не докачался: показываем лоадер -->
            <div class="loader-container" id="waiting-loader" data-url="<?php echo $image_url; ?>">
                <div class="spinner"></div>
                <h2>Файл еще загружается...</h2>
                <p>Страница обновится автоматически, как только скриншот появится на сервере.</p>
            </div>
        <?php endif; ?>
	<?php endif; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>