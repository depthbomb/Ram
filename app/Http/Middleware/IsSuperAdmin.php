<?php namespace Ram\Http\Middleware;

use Closure;

class IsSuperAdmin
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
		$res = $next($request);

		if (is_admin(true)) {
			return $res;
		} else {
			return redirect()->route('index');
		}
	}
}
