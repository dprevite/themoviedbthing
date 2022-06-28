@extends('layout', [
    'page' => 'search',
])

@section('main')
  <form action="/movie" method="get">
    <div class="container">
      <div class="row">
        <div class="col">
          <p>Enter the name of a movie to look it up.</p>
          <div class="input-group mb-3">
            <input type="text" name="query" class="form-control form-control-lg col-6" placeholder="Doctor Strange" tabindex="0" autofocus>
            <button class="btn btn-primary" type="submit" tabindex="1">Search</button>
          </div>

          @if ($nowPlaying !== null)
            <div class="text-center">
              Or try something currently playing:
            </div>
            @foreach ($nowPlaying as $movie)
              <a href="/movie?query={{ urlencode($movie['original_title']) }}"><img src="https://image.tmdb.org/t/p/w185/{{ $movie['poster_path'] }}" alt="{{ $movie['original_title'] }} poster" class="tiny-poster"></a>
            @endforeach
          @endif
        </div>
      </div>
    </div>
  </form>
@endsection
