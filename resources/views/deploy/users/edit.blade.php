@extends('layouts/global', [
	'title' => "Edit: {$user->username}"
])

@section('content')
	<div class="ui container">
		<form class="ui basic form segment">
			<h1 class="ui dividing header">{{ $user->username }}</h1>
			<div class="field">
				<div class="ui right icon input">
					<input type="text" placeholder="Cooldown modifier" value="{{ $user->cooldown_modifier }}">
					<i class="percent icon"></i>
				</div>
			</div>
			<div class="field">
				<div class="ui teal toggle checkbox">
					<input type="checkbox" name="authorized" {{ $user->authorized ? 'checked' : '' }}>
					<label>Authorized</label>
				</div>
			</div>
			<div class="field">
				<div class="ui teal toggle checkbox">
					<input type="checkbox" name="admin" {{ $user->admin ? 'checked' : '' }}>
					<label>Admin</label>
				</div>
			</div>
			<div class="field">
				<div class="ui teal toggle checkbox">
					<input type="checkbox" name="super_admin" {{ $user->super_admin ? 'checked' : '' }}>
					<label>Super Admin</label>
				</div>
			</div>
			<div class="field">
				<button class="ui teal button" type="button">Save</button>
			</div>
		</form>
		<div class="ui basic segment">
			@if ($user->cooldowns->count() > 0)
			<div class="ui relaxed divided list">
				@foreach ($user->cooldowns as $cd)
				<div class="item">
					<i class="large clock middle aligned icon"></i>
					<div class="content">
						<div class="header">{{ ucfirst($cd->type) }} &mdash; Expires in {{ $cd->expiration()->shortAbsoluteDiffForHumans(2) }}</div>
						<div class="description">
							<a href="#" data-cooldown="{{ $cd->uid }}">Expire now</a>
						</div>
					</div>
				</div>
				@endforeach
			</div>
			@else
			<div class="ui center aligned segment">
				<p>No cooldowns</p>
			</div>
			@endif
		</div>
	</div>
@endsection