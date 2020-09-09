<?php namespace Ram\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Cooldown extends Model
{
	public function expiration() : Carbon {
		return Carbon::createFromTimestamp($this->expires);
	}

	public function timeToAvailable() : string {
		$now = time();
		$created = $this->created_at->timestamp;

		return Carbon::createFromTimestamp((($created+3600) - $now) + time())->longAbsoluteDiffForHumans();
	}

	public function recentlyUsed() : bool {
		return (($this->created_at->timestamp + 3600) > time());
	}
}
