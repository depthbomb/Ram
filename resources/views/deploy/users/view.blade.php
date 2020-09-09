@extends('layouts/global', [
	'title' => $user->username
])

@section('content')
	<div class="ui container">
		<div class="ui two column grid">
			<div class="column">
				<div class="ui items">
					<div class="item">
						<div class="image">
							<img src="{{ $user->avatar }}">
						</div>
						<div class="content">
							<div class="ui large header">{{ $user->username }}</div>
							<div class="description">
								<div class="ui list">
									<div class="item">
										<i class="{{ $user->authorized ? 'green check' : 'red close' }} icon"></i>
										<div class="content">
											Authorized
										</div>
									</div>
									<div class="item">
										<i class="{{ $user->admin ? 'green check' : 'red close' }} icon"></i>
										<div class="content">
											Administrator
										</div>
									</div>
									<div class="item">
										<i class="{{ $user->super_admin ? 'green check' : 'red close' }} icon"></i>
										<div class="content">
											Super Administrator
										</div>
									</div>
								</div>
							</div>
							<div class="extra">
								<a href="{{ route('users.edit', $user->steamid) }}" class="ui mini teal button">Edit</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="column">
				@if ($user->cooldowns->count() > 0)
				<div class="ui relaxed divided list">
					@foreach ($user->cooldowns as $cd)
					<div class="item">
						<i class="large clock middle aligned icon"></i>
						<div class="content">
							<div class="header">{{ ucfirst($cd->type) }}</div>
							<div class="description">Expires in {{ $cd->expiration()->shortAbsoluteDiffForHumans(2) }}</div>
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
	</div>
@endsection