@extends('layout', [
    'page' => 'movie',
])

@section('main')

<div class="container">
  <div class="row">
    <div class="col">
      <img src="https://image.tmdb.org/t/p/original/{{ $movie->poster_path }}" alt="{{ $movie->original_title }} poster" class="movie-poster">
    </div>

    <span class="col">
      <h1 class="movie-title">{{ $movie->original_title }}</h1>
      <span class="badge rounded-pill bg-light text-dark">Released {{ date('d-m-Y', strtotime($movie->release_date)) }}</span>
      <span class="badge rounded-pill bg-light text-dark">
        @if (isset($movie->runtime))
          Runtime: {{ intdiv($movie->runtime, 60) . ':' . sprintf('%02d', $movie->runtime % 60) }}
        @else
          Runtime: Unknown
        @endif
      </span>

      <p class="movie-overview">
        {{ $movie->overview }}
      </p>


      <h2>Cast</h2>
      <ul class="movie-cast">
        @foreach ($movie->cast->slice(0, 10) as $cast)
          <li>
            {{ $cast['name'] }} as
            <strong>{{ $cast['character'] }}</strong>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <style>
    body.movie {
      background: rgba(0, 0, 0, 0.7) url("https://image.tmdb.org/t/p/original/{{ $movie->backdrop_path }}");
    }
  </style>
@endsection
