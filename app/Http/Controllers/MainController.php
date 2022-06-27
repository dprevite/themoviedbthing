<?php

namespace App\Http\Controllers;

use App\Libraries\TheMovieDatabase;

class MainController extends Controller
{

    public function showSearchForm(TheMovieDatabase $client)
    {
        $nowPlaying = $client->getNowPlaying();

        return view('search_form', [
            'nowPlaying' => $nowPlaying,
        ]);
    }

}
