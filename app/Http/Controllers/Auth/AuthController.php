<?php namespace Ram\Http\Controllers\Auth;

use DB;
use Ram\Models\User;
use Ehesp\SteamLogin\SteamLogin;
use Illuminate\Http\Request;
use Ram\Http\Controllers\Controller;
use function Opis\Closure\unserialize;

class AuthController extends Controller
{
	public function __construct() {
		$this->login = new SteamLogin();
	}

	public function login(Request $request) {
		if (!auth()->check()) {
			return redirect()->away($this->login->url(route('auth.process')));
		} else {
			return redirect()->route('index');
		}
	}

	public function process() {
		$steamid = $this->login->validate();

		if(isset($steamid)) {
			$user = DB::connection('cyan_users')
				->table('users')
				->select('steamid', 'username', 'avatar', 'permissions')
				->where('steamid', $steamid)
				->first();

			if ($user) {
				$permissions = unserialize($user->permissions);
				if ($permissions['hide_ads'] || $permissions['admin']) {
					$existing_user = User::where('steamid', $steamid)->first();
					if ($existing_user) {
						auth()->login($existing_user);
						return redirect()->route('index');
					} else {
						$u = new User();
						$u->uid = uid(16);
						$u->steamid = $user->steamid;
						$u->username = $user->username;
						$u->avatar = $user->avatar;
						$u->authorized = true;
						$u->admin = $permissions['admin'];
						$u->super_admin = $permissions['can_edit'];
						$u->cooldown_modifier = $permissions['admin'] ? -15 : $permissions['can_edit'] ? -95 : 0;

						if ($u->save()) {
							auth()->login($u);
							return redirect()->route('index');
						} else {
							return abort(500, 'Unable to save new user record.');
						}
					}
				} else {
					return abort(401, 'You do not meet the donor requirements to access the panel.');
				}
			} else {
				return abort(400, 'You do not have an account on the main Cyan.TF site. Please make an account there and then return here.');
			}
		} else {
			return abort(500, 'SteamID not set');
		}
	}

	public function logout() {
		if (auth()->check()) {
			auth()->logout();
		}

		return redirect()->route('index');
	}
}
