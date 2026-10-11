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

	<!-- Значки систем для ссылок скачивания (Simple Icons, CC0) -->
	<symbol id="i-apple" viewBox="0 0 24 24">
		<path fill="currentColor" d="M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.546 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987 1.831 0 2.35.987 3.96.948 1.637-.026 2.676-1.48 3.676-2.948 1.156-1.688 1.636-3.325 1.662-3.415-.039-.013-3.182-1.221-3.22-4.857-.026-3.04 2.48-4.494 2.597-4.559-1.429-2.09-3.623-2.324-4.39-2.376-2-.156-3.675 1.09-4.61 1.09zM15.53 3.83c.843-1.012 1.4-2.427 1.245-3.83-1.207.052-2.662.805-3.532 1.818-.78.896-1.454 2.338-1.273 3.714 1.338.104 2.715-.688 3.559-1.701"/>
	</symbol>
	<symbol id="i-windows" viewBox="0 0 24 24">
		<path fill="currentColor" d="M0 3.449 9.75 2.1v9.451H0m10.949-9.602L24 0v11.4H10.949M0 12.6h9.75v9.451L0 20.699M10.949 12.6H24V24l-12.9-1.801"/>
	</symbol>
	<symbol id="i-server" viewBox="0 0 24 24">
		<g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="7.5" rx="2"/><rect x="3" y="13.5" width="18" height="7.5" rx="2"/><path d="M7 6.75h.01M7 17.25h.01M11 6.75h6M11 17.25h6"/></g>
	</symbol>
</svg>

<header class="topbar">
	<div class="wrap topbar-inner">
		<a class="brand" href="https://yakus.ru/">
			<svg class="brand-mark" aria-hidden="true"><use href="#i-yk"/></svg>
			<span><span class="brand-pre">igor</span>yakus</span>
		</a>
		<nav class="nav">
			<a href="/shothost/#features">Возможности</a>
			<a href="/shothost/#download">Скачать</a>
			<a href="/shothost/#setup">Инструкция</a>
			<?php if (!empty($download_url)): ?>
				<a class="nav-cta" href="<?php echo htmlspecialchars($download_url); ?>" download>Скачать оригинал</a>
			<?php else: ?>
				<a class="nav-cta" href="https://github.com/igoryakus/shothost">GitHub</a>
			<?php endif; ?>
		</nav>
	</div>
</header>
