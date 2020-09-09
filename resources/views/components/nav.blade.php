<nav class="ui large inverted top fixed stackable menu">
	<div class="header item">Cyan.TF Donor Panel</div>
	<div class="left menu">
		<a class="{{ is_active('index') }} teal item" href="{{ route('index') }}">Home</a>
		@if (logged_in())
		<a class="{{ is_active('map.*') }} teal item" href="{{ route('map.index') }}">Map Chooser</a>
		@endif
	</div>
	<div class="right menu">
		<a class="item" href="https://cyan.tf">Main Site</a>
		@if (logged_in())
			@if (is_admin())
			<div class="ui dropdown item">
				Admin <i class="dropdown icon"></i>
				<div class="menu">
					<a class="{{ is_active('users.*') }} item" href="{{ route('users.index') }}">Users</a>
					<a class="{{ is_active('logs.*') }} item">Logs</a>
				</div>
			</div>
			@endif
			<a class="item" href="{{ route('auth.logout') }}">Log Out</a>
		@endif
	</div>
</nav>