<?php namespace Ram\Http\Controllers\Users;

use Ram\Models\User;
use Illuminate\Http\Request;
use Ram\Http\Controllers\Controller;

class UsersController extends Controller
{
	public function index() {
		$users = User::select('avatar', 'username', 'steamid', 'created_at')->get();
		return view('deploy/users/index', compact('users'));
	}

	public function view(string $steamid) {
		$user = User::select('steamid', 'avatar', 'username', 'authorized', 'admin', 'super_admin', 'cooldown_modifier', 'created_at')
		->where('steamid', $steamid)
		->with('cooldowns')
		->first();

		return view('deploy/users/view', compact('user'));
	}

	public function edit(string $steamid) {
		$user = User::select('steamid', 'username', 'authorized', 'admin', 'super_admin', 'cooldown_modifier')
		->where('steamid', $steamid)
		->with('cooldowns')
		->first();

		return view('deploy/users/edit', compact('user'));
	}
}
