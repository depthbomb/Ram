@if ($paginator->hasPages())
	<div class="ui pagination menu" role="navigation">
		{{-- Previous Page Link --}}
		@if ($paginator->onFirstPage())
			<a class="icon item disabled"> Prev </a>
		@else
			<a class="icon item" href="{{ $paginator->previousPageUrl() }}" rel="prev"> Prev </a>
		@endif

		{{-- Pagination Elements --}}
		@foreach ($elements as $element)
			{{-- "Three Dots" Separator --}}
			@if (is_string($element))
				<a class="icon item disabled">{{ $element }}</a>
			@endif

			{{-- Array Of Links --}}
			@if (is_array($element))
				@foreach ($element as $page => $url)
					@if ($page == $paginator->currentPage())
						<a class="item active" href="{{ $url }}">{{ $page }}</a>
					@else
						<a class="item" href="{{ $url }}">{{ $page }}</a>
					@endif
				@endforeach
			@endif
		@endforeach

		{{-- Next Page Link --}}
		@if ($paginator->hasMorePages())
			<a class="icon item" href="{{ $paginator->nextPageUrl() }}" rel="next"> Next </a>
		@else
			<a class="icon item disabled"> Next </a>
		@endif
	</div>
@endif
