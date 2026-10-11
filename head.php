<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo htmlspecialchars($page_title); ?></title>
	<?php if (!empty($page_description)): ?>
	<meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
	<?php endif; ?>
	<meta name="theme-color" content="#000000">
	<link rel="icon" href="/shothost/assets/img/favicon.svg" type="image/svg+xml">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&family=Michroma&display=swap">
	<?php if (!empty($use_viewer)): ?>
	<!-- Стили плагина Viewer.js -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.7/viewer.min.css">
	<?php endif; ?>
	<link rel="stylesheet" href="/shothost/assets/style.css?v=2">
</head>
<body class="<?php echo htmlspecialchars($body_class ?? ''); ?>">
