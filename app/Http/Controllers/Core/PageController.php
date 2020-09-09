<?php namespace Ram\Http\Controllers\Core;

use Ram\Models\Cooldown;
use Illuminate\Http\Request;
use Ram\Http\Controllers\Controller;

class PageController extends Controller
{
	public function index() {
		if (logged_in()) {
			$cooldowns = Cooldown::select('type', 'expires')->where([
				['user_steamid', auth()->user()->steamid],
				['expired', false]
			])->get();
			return view('deploy/index', compact('cooldowns'));
		} else {
			return view('deploy/login');
		}
	}
}
