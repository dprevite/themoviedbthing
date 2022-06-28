<?php

namespace App\Http\Controllers;

use App\Libraries\TheMovieDatabase;
use Illuminate\Http\Request;

class MovieController extends Controller
{

    public function show(Request $request, TheMovieDatabase $client)
    {
        try {
            $movie = $client->findMatchingMovie($request->input('query'));
        } catch (\Exception $e) {
            return redirect('/')->withErrors($e->getMessage());
        }

        return view('movie', [
            'movie' => $movie,
        ]);
    }


}
