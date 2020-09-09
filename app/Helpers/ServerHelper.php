<?php namespace Ram\Helpers;

use \xPaw\SourceQuery\SourceQuery;

class ServerHelper
{
	public static $address = config('ram.server.address');
	public static $port = config('ram.server.port');
	public static $rcon = config('ram.server.rcon');
	public static $timeout = 1;
	public static $engine = SourceQuery::SOURCE;

	public static function sendCommand($input) {
		try {
			try {
				$q = new SourceQuery();
				$q->Connect(self::$address, self::$port, self::$timeout, self::$engine);
				$q->SetRconPassword(self::$rcon);
			} catch (\xPaw\SourceQuery\Exception\SocketException $e) {}
		
			if (is_array($input)) {
				$commands = implode(';', $input);
				return $q->Rcon($commands);
			} else {
				return $q->Rcon($input);
			}
		} catch (Exception $e) {
			return [$e->getMessage()];
		} finally {
			$q->Disconnect();
		}
	}

	public static function getPlayers($preCommand = null) : array {
		try {
			try {
				$q = new SourceQuery();
				$q->Connect(self::$address, self::$port, self::$timeout, self::$engine);
				$q->SetRconPassword(self::$rcon);
			} catch (\xPaw\SourceQuery\Exception\SocketException $e) {}

			if ($preCommand !== null) $q->Rcon($preCommand);

			$status = $q->Rcon('status'); // the "status" command returns a large string that also contains player data
			$status = explode("\n", $status); // turn the output into an array
			$status = array_slice($status, 10); // index 0 - 9 are useless and should be removed
			array_pop($status); // remove the last item which is just blank

			$players = []; // empty array to push the player data to

			// iterate through the player lines
			foreach ($status as $s) {
				preg_match_all("/[^\s\"']+|\"(?:[^\"]*)\"/", $s, $split); // split the "sections"
				
				$split = array_flatten($split); // flatten out the array because it is needlessly interdimensional
				array_shift($split); // remove the first item (#) from the array

				$arr = [
					'id' => $split[0],
					'name' => trim($split[1], '"'),
					'steamid' => $split[2],
					'time' => $split[3],
					'ping' => $split[4],
					'loss' => $split[5],
					'state' => $split[6],
					'ip' => $split[7]
				];

				$players[] = $arr; // push the array onto the empty array
			}

			return $players; // boom! 
		} catch (Exception $e) {
			return [$e->getMessage()];
		} finally {
			$q->Disconnect();
		}
	}

	public static function printToConsole($msg) : void {
		self::sendCommand("sm_printcon {$msg}");
	}

	public static function getTimeleft(bool $dateObject = false) {
		$output = trim(self::sendCommand('timeleft'));
		if (preg_match("/This is the last round\!\!/", $output)) {
			return (object) date_parse('00:00:00');
		} else if (preg_match("/(\d{1,2}:\d{1,2})/", $output, $match)) {
			$timeleft = $match[1];
			if ($dateObject) {
				if (preg_match("/(?:(\d{1,2}):)?(\d{1,2}):(\d{1,2})/", $timeleft, $out)) {
					$hr = $out[1];
					if ($hr === '') {
						return (object) date_parse('00:' . $timeleft);
					} else {
						return (object) date_parse($timeleft);
					}
				} else {
					return (object) date_parse('00:00:00');
				}
			} else {
				return (string) $timeleft;
			}
		}
	}
}