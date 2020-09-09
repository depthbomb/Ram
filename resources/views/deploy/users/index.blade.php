@extends('layouts/global', [
	'title' => 'Users'
])

@section('content')
	<div class="ui container">
		<h1 class="ui dividing header">
			Registered Users
		</h1>
		<div class="ui five column grid">
			@foreach ($users as $user)
			<div class="ui three wide column">
				<div class="ui card">
					<div class="image">
						<img src="{{ $user->avatar }}" title="{{ $user->username }}">
					</div>
					<div class="center aligned content">
						<div class="header">{{ $user->username }}</div>
						<div class="meta">
							<span class="date">{{ $user->created_at->shortAbsoluteDiffForHumans(2) }}</span>
						</div>
					</div>
					<div class="center aligned extra content">
						<a href="{{ route('users.view', $user->steamid) }}">Info</a>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
@endsection