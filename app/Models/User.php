<?php namespace Ram\Models;

use Ram\Models\Cooldown;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class User extends Model implements Authenticatable
{
	use \Illuminate\Auth\Authenticatable;

	public function cooldowns() {
		return $this->hasMany('Ram\Models\Cooldown', 'user_steamid', 'steamid')->where('expired', false);
	}

	public function addCooldown(User $user, int $minutes, string $type = 'map') : bool {
		$steamid = $user->steamid;
		$modifier = $user->cooldown_modifier;

		$cooldown_duration = round($minutes *= (1 + $modifier / 100));
		$cooldown = now()->addMinutes($cooldown_duration)->timestamp;

		$cd = new Cooldown();
		$cd->uid = uid(32);
		$cd->user_steamid = $steamid;
		$cd->type = $type;
		$cd->expires = $cooldown;
		$cd->expired = false;
		return $cd->save();
	}

	public function lastCooldown(string $type = 'map') {
		$cd = Cooldown::where([
			['user_steamid', $this->steamid],
			['type', $type],
			['expires', '>', time()],
			['expired', false]
		])->first();

		return $cd;
	}

	public function hasCooldown(string $type = 'map') : bool {
		$cd = Cooldown::where([
			['user_steamid', $this->steamid],
			['type', $type],
			['expires', '>', time()],
			['expired', false]
		])->exists();

		return $cd;
	}
}
