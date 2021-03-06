<?php

namespace App\Http\Controllers;

use App\Libraries\TheMovieDatabase;

class MainController extends Controller
{

    public function showSearchForm(TheMovieDatabase $client)
    {
        try {
            $nowPlaying = $client->getNowPlaying();
        } catch (\Exception $e) {
            $nowPlaying = null;
        }

        return view('search_form', [
            'nowPlaying' => $nowPlaying,
        ]);
    }

}
