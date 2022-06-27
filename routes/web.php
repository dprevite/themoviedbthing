<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureMovieRequestIsValid;

Route::get('/', [\App\Http\Controllers\MainController::class, 'showSearchForm']);
Route::get('/movie', [\App\Http\Controllers\MovieController::class, 'show'])
    ->middleware(EnsureMovieRequestIsValid::class);

