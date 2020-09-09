<?php

function boot_config(bool $raw = false) {
	$config = [
		'locale' => app()->getLocale(),
		'environment' => app()->environment(),
		'isStaging' => str_contains(app()->environment(), ['local', 'staging', 'debug']),
		'serverTime' => time(),
		'serverTimeFloat' => microtime(true),
		'requestIp' => request()->ip(),
		'requestUri' => url()->current(),
		'routeName' => \Route::currentRouteName(),
		'routeParams' => \Route::current()->parameters(),
		'csrfToken' => csrf_token(),
		'ui' => [],
		'features' => [
			'map' => []
		]
	];

	if ($raw) return $config;
	return json_encode($config);
}


function setting(string $key, $default = null) {
	$key = "setting/$key";
	if (cache()->has($key)) {
		$settings = cache()->get($key);
	} else {
		if ($settings = \Ram\Models\Setting::select('value')->where('key', $key)->first()) {
			cache()->put($key, $settings, now()->addDays(365));
		} else {
			$settings = null;
		}
	}

	return $settings === null ? $default : $settings->value;
}


function u(string $path = '') : string {
	return sprintf(
		'%s://%s%s%s',
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		$_SERVER['SERVER_NAME'],
		isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 8000 ? ":{$_SERVER['SERVER_PORT']}" : '',
		$path
	);
}


function app_asset(string $path) {
	$key = "app/asset_tag/$path";
	if (cache()->has($key)) {
		$tag = cache()->get($key);
	} else {
		$asset_path = rev($path, true);
		$url = str_replace('www.', '', rev($path));
		$ext = pathinfo($path, PATHINFO_EXTENSION);

		$public_path = public_path(ltrim($asset_path, '/'));

		switch($ext) {
			case 'css':
				$hash = [
					'sha512-' . base64_encode(hash_file('sha512', $public_path, true)),
					'sha384-' . base64_encode(hash_file('sha384', $public_path, true)),
					'sha256-' . base64_encode(hash_file('sha256', $public_path, true)),
				];
				$tag = "<link rel=\"stylesheet\" href=\"$url\" integrity=\"".implode(' ', $hash)."\" type=\"text/css\">";
				break;
			case 'js':
				$hash = [
					'sha512-' . base64_encode(hash_file('sha512', $public_path, true)),
					'sha384-' . base64_encode(hash_file('sha384', $public_path, true)),
					'sha256-' . base64_encode(hash_file('sha256', $public_path, true)),
				];
				$tag = "<script crossorigin=\"anonymous\" src=\"$url\" integrity=\"".implode(' ', $hash)."\" type=\"application/javascript\"></script>";
				break;
			default:
				return "<!--	INVALID ASSET EXT	-->";
				break;
		}

		cache()->put($key, $tag, now()->addDays(31));
	}

	return new \Illuminate\Support\HtmlString($tag);
}


/**
 * Generates a "Unique ID"
 *
 * @param integer $length
 * @param integer $seed
 * @param string $characters
 * @return string
 */
function uid(int $length = 8, int $seed = null, string $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_') : string {
	do {
		$valid_seed = false;
		if ($seed !== null && is_int($seed)) {
			$valid_seed = true;
			srand($seed);
		}
		$charactersLength = strlen($characters);

		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		$messy = (starts_with($randomString, ['-']) || ends_with($randomString, ['-', '_']));
		if ($messy && $valid_seed) {
			$seed++;
		}

	} while($messy);

	return $randomString;
}


function simplified_fnmatch(string $pattern, string $string): bool {
	// fnmatch is a function available on linux systems, which sees if a $pattern string with wildcards matches a string, using linux shell rules
	// the simplified_fnmatch function escapes ?,[], and {} characters, so as to only apply the * wildcard
	// it also uses the FNM_CASEFOLD flag, since in URLs case does not matter
	$escaped_pattern = '';
	for ($i = 0; $i < strlen($pattern); $i++)
	{
		if (($pattern[$i] == '?') || ($pattern[$i] == '[') || ($pattern[$i] == ']') || ($pattern[$i] == '{') || ($pattern[$i] == '}')) $escaped_pattern .= '\\';
		$escaped_pattern .= $pattern[$i];
	}
	return fnmatch($escaped_pattern, $string, FNM_CASEFOLD);
}


function px() : string {
	return 'data:image/gif;base64,R0lGODlhAQABAPAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
}


function present(string $string) : bool {
	return $string !== null && $string !== '';
}


function route_is(string $route_name) : bool {
	return Route::currentRouteName() === $route_name;
}


/**
 * is_active
 * Returns a CSS class name if the current application route is $route
 *
 * @param string $route
 * @param string $class
 * @return string
 */
function is_active(string $route, string $class = 'active') : string {
	$currentRoute = Route::currentRouteName();
	if ($currentRoute === null) {
		return 'inactive';
	} else {
		return simplified_fnmatch($route, $currentRoute) ? $class : 'inactive';
	}
}


function utf8_field() {
	return new \Illuminate\Support\HtmlString('<input type="hidden" name="utf8" value="&#x2713;">');
}


/**
* Bust
*
* Creates a formatted URL to the provided path and appends a cachebuster string to the end
* @param string $url path to file
* @return string
*/
function bust(string $path) : string {
	$seed = filemtime(public_path($path));
	$hash = uid(32, $seed, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
	return u("{$path}?v={$hash}");
}


/**
 * re
 *
 * Returns a redirect route URL to the provided destination
 * @param string $url
 * @return string
 */
function re(string $url) : string {
	$u = $url;
	$h = hash('adler32', strrev($url));
	return route('redirect', compact('u', 'h'));
}


/**
* Asset revision helper
* @param  string $p Absolute path to file relative to /public/  (include beginning slash)
* @return string
*/
function rev(string $p, bool $pathOnly = false) : string {
	$config = config('ram.assets.files');
	if (array_key_exists($p, $config)) {
		$revisioned = $config[$p];
		if ($pathOnly) {
			return $revisioned;
		} else {
			return u($revisioned);
		}
	} else {
		return u($p . '?KEY_NOT_FOUND');
	}
}


function plural_label(string $label, int $count) : string {
	return $count . ' ' . str_plural($label, $count);
}


function time_element($date, bool $inverse = false) {
	if ($inverse) {
		$element = "<time datetime=\"{$date->format('c')}\" data-time=\"{$date->diffForHumans()}\">{$date->toDayDateTimeString()}</time>";
	} else {
		$element = "<time datetime=\"{$date->format('c')}\" data-time=\"{$date->toDayDateTimeString()}\">{$date->diffForHumans()}</time>";
	}
	return new \Illuminate\Support\HtmlString($element);
}


function is_sql_unique_exception($ex) : bool {
	return starts_with(
		$ex->getMessage(),
		'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry'
	);
}


function logged_in() : bool {
	return auth()->check();
}


function is_admin(bool $super_admin = false) : bool {
	if (logged_in()) {
		if ($super_admin) {
			return auth()->user()->super_admin;
		} else {
			return auth()->user()->admin;
		}
	} else {
		return false;
	}
}


function weighted(array $weightedValues) : string {
	$rand = mt_rand(1, (int) array_sum($weightedValues));
	foreach ($weightedValues as $key => $value) {
		$rand -= $value;
		if ($rand <= 0) {
			return $key;
		}
	}
}


function number_format_short($n, $precision = 1) {
	if ($n < 900) {
		// 0 - 900
		$n_format = number_format($n, $precision);
		$suffix = '';
	} else if ($n < 900000) {
		// 0.9k-850k
		$n_format = number_format($n / 1000, $precision);
		$suffix = 'K';
	} else if ($n < 900000000) {
		// 0.9m-850m
		$n_format = number_format($n / 1000000, $precision);
		$suffix = 'M';
	} else if ($n < 900000000000) {
		// 0.9b-850b
		$n_format = number_format($n / 1000000000, $precision);
		$suffix = 'B';
	} else {
		// 0.9t+
		$n_format = number_format($n / 1000000000000, $precision);
		$suffix = 'T';
	}
  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
	if ($precision > 0) {
		$dotzero = '.' . str_repeat('0',$precision);
		$n_format = str_replace($dotzero, '', $n_format);
	}
	return $n_format . $suffix;
}