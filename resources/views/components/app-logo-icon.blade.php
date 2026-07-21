@php
	$variant = $attributes->get('variant', 'wave');
	$class = trim('soh-logo-mark ' . $attributes->get('class'));
@endphp

<svg {{ $attributes->except(['variant', 'class'])->merge(['class' => $class, 'viewBox' => '0 0 96 96', 'width' => '96', 'height' => '96', 'fill' => 'none', 'xmlns' => 'http://www.w3.org/2000/svg', 'role' => 'img', 'aria-label' => 'SOHMC logo']) }}>
	@if ($variant === 'note')
		<defs>
		<path d="M48 13L75 25V50C75 68.45 61.84 82.95 48 89C34.16 82.95 21 68.45 21 50V25L48 13Z" fill="url(#soh-crest-gradient)" />
		<rect x="17" y="34" width="62" height="23" rx="11.5" fill="#A6128D" opacity="0.99" />
		<text x="30.5" y="49.5" text-anchor="middle" fill="#FFFFFF" font-size="12.8" font-weight="900">♪</text>
		<text x="50.5" y="49.5" text-anchor="middle" fill="#FFFFFF" font-size="11.6" font-weight="900" letter-spacing="0.45">SOHMC</text>
			</linearGradient>
		</defs>
		<rect x="15" y="15" width="66" height="66" rx="21" fill="#A6128D" />
		<path d="M57 23V56.2C55.17 54.6 52.77 53.8 50 53.8C44.24 53.8 40 57.75 40 63.1C40 68.45 44.24 72.4 50 72.4C55.76 72.4 60 68.45 60 63.1V33.4L71 31V25.2L57 23Z" fill="url(#soh-note-gradient)" />
		<path d="M53 29L63 31.2V25.8L53 24V29Z" fill="#FFFFFF" opacity="0.9" />
	@elseif ($variant === 'crest')
		<defs>
			<linearGradient id="soh-crest-gradient" x1="20" y1="18" x2="76" y2="78" gradientUnits="userSpaceOnUse">
				<stop stop-color="#FFFFFF" />
				<stop offset="1" stop-color="#D991CD" />
			</linearGradient>
		</defs>
		<path d="M48 10L78 23V50C78 68.13 65.05 82.28 48 90C30.95 82.28 18 68.13 18 50V23L48 10Z" fill="#A6128D" />
		<path d="M48 15L73 26V50C73 66.7 60.68 79.78 48 86C35.32 79.78 23 66.7 23 50V26L48 15Z" fill="url(#soh-crest-gradient)" />
		<rect x="22" y="35" width="52" height="21" rx="10.5" fill="#A6128D" opacity="0.99" />
		<text x="48" y="49.6" text-anchor="middle" fill="#FFFFFF" font-size="11.8" font-weight="900" letter-spacing="0.55">SOHMC</text>
	@else
		<defs>
			<linearGradient id="soh-wave-gradient" x1="18" y1="18" x2="78" y2="78" gradientUnits="userSpaceOnUse">
				<stop stop-color="#FFFFFF" />
				<stop offset="1" stop-color="#D991CD" />
			</linearGradient>
		</defs>
		<rect x="14" y="14" width="68" height="68" rx="22" fill="#A6128D" />
		<path d="M23 58.2C29.28 49.4 36.12 45 43.5 45C47.28 45 50.62 46.1 53.5 48.3C55.86 50.1 58.12 52.54 60.3 55.6C62.14 58.15 64.09 60.08 66.18 61.4C68.05 62.58 70.05 63.2 72.2 63.2C75.42 63.2 78 62.17 80 60.1V69.2C76.76 71.13 73.56 72.1 70.4 72.1C65.73 72.1 61.55 70.12 57.85 66.15C56.13 64.3 54.53 62.18 53.04 59.8C50.69 56.05 47.74 54.2 44.18 54.2C39.59 54.2 34.7 57.62 29.5 64.45L23 58.2Z" fill="url(#soh-wave-gradient)" />
		<circle cx="31" cy="34" r="4" fill="#FFFFFF" />
	@endif
</svg>
