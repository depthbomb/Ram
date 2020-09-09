<?php namespace Ram\Http\Controllers\Map;

use Ram\Models\Cooldown;
use Illuminate\Http\Request;
use Ram\Http\Controllers\Controller;

class MapController extends Controller
{
	public function __construct() {
		$this->cooldown = config('ram.features.maps.cooldown');
		$this->maps = config('ram.features.map.available');
	}

	public function index() {
		$user = auth()->user();
		$steamid = $user->steamid;

		$last_cooldown = Cooldown::select('created_at')
			->where([
				['type', 'map'],
				['user_steamid', '!=', $steamid],
				['expired', false]
			])
			->latest()
			->first() ?? null;

		return view('deploy/map/index', ['last_cooldown' => $last_cooldown, 'maps' => $this->maps]);
	}
}
