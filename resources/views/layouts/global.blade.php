<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>{{ $title ?? 'Cyan.TF Donor Panel' }}</title>
		<!--[if lt IE 9]><link rel="shortcut icon" type="image/x-icon" href="{{ bust('/favicon.ico') }}">-->
		<link rel="icon" type="image/ico" href="{{ bust('/favicon.ico') }}">
		<link rel="canonical" href="{{ route('index') }}">
		<meta name="robots" content="index,follow">
		<meta name="application/config" content="{{ boot_config() }}">
		{{ app_asset('/assets/css/app.css') }}
		@stack('css')
	</head>
	<body>
		@include('components/nav')
		<div class="app">
			<aside class="ui global blue message" role="alert">
				<p>The donor panel is currently in beta. Occasionally, the database may be wiped. However, nothing important will be lost except your cooldowns. But would you really be upset about that?</p>
			</aside>
			<div class="ui basic padded segment">
				@yield('content')
			</div>
		</div>
		<div class="ui vertical inverted footer segment">
			<div class="ui center aligned container">
				<p><i class="copyright icon"></i> {{ date('Y') }} Caprine Softworks</p>
			</div>
		</div>
		{{ app_asset('/assets/js/app.js') }}
	</body>
</html>