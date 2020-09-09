<?php namespace Ram\Http\Controllers\Ajax;

use Ram\Models\Cooldown;
use Ram\Helpers\ServerHelper;
use Illuminate\Http\Request;
use Ram\Http\Controllers\Controller;

class MapController extends Controller
{
	public function __construct() {
		(int)   $this->cooldown   = config('ram.features.map.cooldown');
		(array) $this->valid_maps = collect(config('ram.features.map.available'))->pluck('title')->toArray();
	}

	public function setNextMap(Request $request) {
		$user = auth()->user();
		if ($user->hasCooldown('map')) {
			return [
				'success' => false,
				'message' => 'Nice try, but you currently have a map cooldown.',
				'results' => null
			];
		}
		(string) $map = trim($request['map']);
		if (in_array($map, $this->valid_maps)) {
			$timeleft = ServerHelper::getTimeleft(true);
			if ($timeleft->minute >= 6) {
				$username = $user->username;
				if ($user->addCooldown($user, $this->cooldown, 'map')) {
					ServerHelper::sendCommand([
						"sm_setnextmap {$map}",
						"sm_say  ",
						"sm_say  ",
						"sm_say Next map has been set to {$map} by {$username} from the Cyan.TF donor panel.",
						"sm_say  ",
						"sm_say  ",
						"sm_hsay Next map has been set to {$map} by {$username} from the Cyan.TF donor panel.",
					]);

					return [
						'success' => true,
						'message' => null,
						'results' => null
					];
				} else {
					return [
						'success' => false,
						'message' => 'Failed to save cooldown',
						'results' => null
					];
				}
			} else {
				return [
					'success' => false,
					'message' => 'You may not set the next map if there is less than 6 minutes left. Please try again shortly.',
					'results' => null
				];
			}
		} else {
			return [
				'success' => false,
				'message' => 'Invalid map',
				'results' => null
			];
		}
	}
}
