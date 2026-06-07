<header>
	<div class="logo-container">
		<a href="/screenshots-uploader/" class="logo-link">
			<div class="logo">Screenshots uploader</div>
		</a>
        <a href="/screenshots-uploader/" class="nav-link about-link">
            <svg height="18" viewBox="0 0 24 24" width="18" class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <span>О проекте</span>
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
	<?php if (isset($is_home) && !$is_home && isset($image_url)): ?>
		<a href="<?php echo $image_url; ?>" download class="btn-download">Скачать оригинал</a>
	<?php endif; ?>
</header>
