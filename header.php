<svg class="sprite" aria-hidden="true" focusable="false">
	<defs>
		<linearGradient id="g-yk" gradientUnits="userSpaceOnUse" x1="0" y1="0" x2="100" y2="100">
			<stop offset="0" stop-color="#1b1640"/>
			<stop offset="1" stop-color="#07070f"/>
		</linearGradient>
		<linearGradient id="g-yk-c" gradientUnits="userSpaceOnUse" x1="0" y1="20" x2="0" y2="80">
			<stop offset="0" stop-color="#ffe14d"/>
			<stop offset="1" stop-color="#ff3d8b"/>
		</linearGradient>
		<filter id="f-yk-glow" x="-30%" y="-30%" width="160%" height="160%">
			<feGaussianBlur stdDeviation="2.6" result="blur"/>
			<feMerge>
				<feMergeNode in="blur"/>
				<feMergeNode in="SourceGraphic"/>
			</feMerge>
		</filter>
		<linearGradient id="g-sc" gradientUnits="userSpaceOnUse" x1="0" y1="0" x2="100" y2="100">
			<stop offset="0" stop-color="#ff3d8b"/>
			<stop offset="0.5" stop-color="#ff8a3d"/>
			<stop offset="1" stop-color="#7a5cff"/>
		</linearGradient>
		<radialGradient id="g-shine" cx="0.2" cy="0.1" r="0.9">
			<stop offset="0" stop-color="#fff" stop-opacity="0.35"/>
			<stop offset="0.6" stop-color="#fff" stop-opacity="0"/>
		</radialGradient>
	</defs>

	<!-- Знак автора: буква y из yakus и мигающий курсор, как на yakus.ru -->
	<symbol id="i-yk" viewBox="0 0 100 100">
		<rect width="100" height="100" rx="24" fill="url(#g-yk)"/>
		<path d="M26 30l14 24M54 30 34 76" fill="none" stroke="url(#g-yk-c)" stroke-width="9" stroke-linecap="round" stroke-linejoin="round" filter="url(#f-yk-glow)"/>
		<rect x="62" y="62" width="14" height="16" rx="2" fill="url(#g-yk-c)" filter="url(#f-yk-glow)">
			<animate attributeName="opacity" values="1;1;0;0" keyTimes="0;0.5;0.5;1" dur="1.1s" repeatCount="indefinite"/>
		</rect>
	</symbol>

	<!-- ShotHost: рамка видоискателя и стрелка загрузки -->
	<symbol id="i-sc" viewBox="0 0 100 100">
		<rect width="100" height="100" rx="24" fill="url(#g-sc)"/>
		<rect width="100" height="100" rx="24" fill="url(#g-shine)"/>
		<path d="M24 40V30a6 6 0 0 1 6-6h10M60 24h10a6 6 0 0 1 6 6v10M76 60v10a6 6 0 0 1-6 6H60M40 76H30a6 6 0 0 1-6-6V60" fill="none" stroke="#fff" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/>
		<path d="M50 66V36M38 47l12-12 12 12" fill="none" stroke="#fff" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/>
	</symbol>
</svg>

<header class="topbar">
	<div class="wrap topbar-inner">
		<a class="brand" href="https://yakus.ru/">
			<svg class="brand-mark" aria-hidden="true"><use href="#i-yk"/></svg>
			<span><span class="brand-pre">igor</span>yakus</span>
		</a>
		<nav class="nav">
			<a href="/shothost/"<?php echo ($current_page ?? '') === 'home' ? ' class="is-active"' : ''; ?>>О проекте</a>
			<a href="/shothost/docs/"<?php echo ($current_page ?? '') === 'docs' ? ' class="is-active"' : ''; ?>>Инструкция</a>
			<?php if (!empty($download_url)): ?>
				<a class="nav-cta" href="<?php echo htmlspecialchars($download_url); ?>" download>Скачать оригинал</a>
			<?php else: ?>
				<a class="nav-cta" href="https://github.com/igoryakus/shothost">GitHub</a>
			<?php endif; ?>
		</nav>
	</div>
</header>
