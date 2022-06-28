<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;

class TheMovieDatabase {

    const BASE_URL = 'https://api.themoviedb.org/3/';

    public function findMatchingMovie(string $query)
    {
        $result = $this->get('search/movie', [
            'query' => $query,
        ])[0];

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

    private function get(string $url, array $params = [], string $results = 'results') {
        $response = Http::get(self::BASE_URL . $url, array_merge($params, [
            'api_key' => config('themoviedb.api_key'),
        ]));

        if (!$response->successful()) {
            throw new \Exception('Failure to get data from TheMovieDatabase.');
        }

        return collect($response->json()[$results]);
    }

}
