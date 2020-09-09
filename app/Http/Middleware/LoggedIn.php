<?php namespace Ram\Http\Middleware;

use Closure;

class LoggedIn
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (!logged_in()) {
			return redirect()->route('index');
		}

		return $next($request);
	}
}
