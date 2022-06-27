<?php

namespace App\Http\Controllers;

use App\Libraries\TheMovieDatabase;
use Illuminate\Http\Request;

class MovieController extends Controller
{

    protected $client;

    public function show(Request $request, TheMovieDatabase $client)
    {
        $this->client = $client;

        $movie = $client->findMatchingMovie($request->input('query'));

        if ($movie == null) {
            return redirect('/')->withErrors('Could not find any matching movie');
        }

        return view('movie', [
            'movie' => $movie,
        ]);
    }


}
