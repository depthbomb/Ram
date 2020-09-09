@extends('layouts/global')

@section('content')
	<div class="ui container">
		<h1 class="ui dividing header">
			Welcome back, {{ auth()->user()->username }}!
			<div class="sub header">Here you can access features only available to donors. Each feature has its own cooldown and the cooldowns' durations varies.</div>
		</h1>
		<div class="ui two column grid">
			<div class="five wide column">
				<div class="ui top attached center aligned header">Cooldown Modifier</div>
				<div class="ui attached center aligned segment">
					<div class="ui small teal statistic">
						<div class="value">
							{{ auth()->user()->cooldown_modifier }}%
						</div>
					</div>
				</div>
				<div class="ui attached center aligned header">Cooldowns</div>
				@if ($cooldowns->count() > 0)
				<table class="ui bottom attached celled teal table">
					<tbody>
						@foreach ($cooldowns as $cd)
						<tr class="center aligned">
							<td data-label="Type">{{ ucfirst($cd->type) }}</td>
							<td data-label="Expires">Expires in {{ $cd->expiration()->longAbsoluteDiffForHumans(2) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				@else
				<div class="ui bottom attached center aligned segment">
					<p>Wowie! You have no active cooldowns!</p>
				</div>
				@endif
			</div>
			<div class="eleven wide column">
				<div class="ui equal width grid">
					<div class="column">
						<a href="{{ route('map.index') }}" class="ui large fluid teal button">Map Chooser</a>
					</div>
					<div class="column">
						<a href="#" class="ui large fluid teal disabled button">Player Slayer</a>
					</div>
					<div class="column">
						<a href="#" class="ui large fluid teal disabled button">Ban Everyone</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection