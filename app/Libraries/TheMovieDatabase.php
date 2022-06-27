<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;

class TheMovieDatabase {

    public function findMatchingMovie(string $query)
    {
        try {
            $response = Http::get('https://api.themoviedb.org/3/search/movie', [
                'api_key' => config('themoviedb.api_key'),
                'query'   => $query,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Could not find matching movie');
            }

            $result = $response->json()['results'][0] ?? [];
        } catch (\Exception $e) {
            return null;
        }

        $result['cast'] = $this->findMovieCast($result);

        return (object)$result;
    }


    public function findMovieCast(array $movie)
    {
        try {
            $response = Http::get('https://api.themoviedb.org/3/movie/' . $movie['id'] . '/credits', [
                'api_key' => config('themoviedb.api_key'),
            ]);

            if (!$response->successful()) {
                throw new \Exception('Could not find movie cast');
            }

            $result = collect($response->json()['cast']) ?? null;
        } catch (\Exception $e) {
            return null;
        }

        #var_dump($result); exit;

        return $result;
    }


    public function getNowPlaying() {
        try {
            $response = Http::get('https://api.themoviedb.org/3/movie/now_playing', [
                'api_key' => config('themoviedb.api_key'),
            ]);

            if (!$response->successful()) {
                throw new \Exception('Could not find now playing movies');
            }

            return collect($response->json()['results'])->take(5);
        } catch (\Exception $e) {
            return null;
        }
    }



}
