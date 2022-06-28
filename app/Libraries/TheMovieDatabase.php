<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;

class TheMovieDatabase {

    const BASE_URL = 'https://api.themoviedb.org/3/';

    public function findMatchingMovie(string $query)
    {
        $results = $this->get('search/movie', [
            'query' => $query,
        ]);

        if (!isset($results[0])) {
            throw new \Exception('Could not find any matching movie');
        }

        $result = $this->get('movie/' . $results[0]['id'], [], null);

        $result['cast'] = $this->findMovieCast($result['id']);

        return (object)$result;
    }

    public function findMovieCast(int $movieId)
    {
        $result = $this->get('movie/' . $movieId . '/credits', [], 'cast');

        return collect($result);
    }

    public function getNowPlaying() {
        $result = $this->get('movie/now_playing');

        return collect($result)->take(5);
    }

    private function get(string $url, array $params = [], ?string $resultsProperty = 'results') {
        $response = Http::get(self::BASE_URL . $url, array_merge($params, [
            'api_key' => config('themoviedb.api_key'),
        ]));

        if (!$response->successful()) {
            throw new \Exception('Failure to get data from TheMovieDatabase.');
        }

        if ($resultsProperty == null) {
            return $response->json();
        }

        return collect($response->json()[$resultsProperty]);
    }

}
