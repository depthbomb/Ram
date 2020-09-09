@extends('layouts/global', [
	'title' => 'Map Chooser'
])

@section('content')
	<div class="ui container">
		<h1 class="ui horizontal divider header">
			Map Chooser
		</h1>

		<p class="lead">Set the server's <u>next map</u> to one from the off-rotation. Do not change the map if you don't intend to play on it. This feature has a 3-day cooldown.</p>

		@if (auth()->user()->hasCooldown('map'))
			<aside class="ui huge red message">You are currently under a cooldown that expires in {{ auth()->user()->lastCooldown('map')->expiration()->longAbsoluteDiffForHumans(2) }}.</aside>
		@else
			@if ($last_cooldown && $last_cooldown->recentlyUsed('map'))
			<aside class="ui orange message">
				<div class="content">
					<div class="header">Attention</div>
				</div>
				<p>The map chooser was recently used by another donor. You may use the map chooser in {{ $last_cooldown->timeToAvailable() }}.</p>
			</aside>
			@else
			<div class="ui two column grid">
				<div class="ten wide column">
					<div class="ui huge fluid search selection dropdown">
						<input type="hidden" name="map">
						<i class="dropdown icon"></i>
						<div class="default text">Select a map</div>
						<div class="menu">
							@foreach ($maps as $map)
							<div class="item" data-value="{{ $map['title'] }}">
								[{{ $map['category'] }}] <strong>{{ $map['title'] }}</strong>
							</div>
							@endforeach
						</div>
					</div>
				</div>
				<div class="six wide column">
					<button class="ui huge fluid blue submit button" type="button">Submit</button>
				</div>
			</div>
			@endif
		@endif
	</div>
@endsection