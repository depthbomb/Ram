@extends('layouts/global', [
	'title' => 'Login'
])

@section('content')
	<div class="ui container">
		<h1 class="ui dividing header">Welcome to the Cyan.TF Donor Panel</h1>
		<p class="lead">The donor panel is where you can access some cool server-related features from the comfort of your web browser! To gain access to the panel, you must be a $20 tier donor and you must have an account created on the <a href="https://cyan.tf" target="_blank">Cyan.TF website.</a></p>
		<p class="lead">Once you meet those two requirements, simply log in here and you're set!</p>

		<div class="ui two column grid">
			<div class="six wide column">
				<a class="ui fluid large olive labeled icon button" href="{{ route('auth.login') }}"><i class="steam icon"></i> Sign in through Steam</a>
			</div>
			<div class="ten wide column">
				
			</div>
		</div>
	</div>
@endsection