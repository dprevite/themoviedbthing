<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use App\Libraries\TheMovieDatabase;

class ClientTest extends TestCase
{

    public function test_get_now_playing()
    {
        Http::preventStrayRequests();

        Http::fake([
            TheMovieDatabase::BASE_URL . 'movie/now_playing?api_key=1111' => $this->get_mock_response('now_playing.json')
        ]);

        $client = new TheMovieDatabase();

        $nowPlaying = $client->getNowPlaying();

        Http::assertSentCount(1);

        $this->assertTrue($nowPlaying[0]['original_title'] === 'Doctor Strange in the Multiverse of Madness');
        $this->assertTrue($nowPlaying[0]['poster_path'] === '/9Gtg2DzBhmYamXBS1hKAhiwbBKS.jpg');

        $this->assertTrue($nowPlaying->count() === 5);
    }

    public function test_find_movie_cast()
    {
        Http::preventStrayRequests();

        Http::fake([
            TheMovieDatabase::BASE_URL . 'movie/453395/credits?api_key=1111' => $this->get_mock_response('movie_cast.json')
        ]);

        $client = new TheMovieDatabase();

        $cast = $client->findMovieCast(453395);

        Http::assertSentCount(1);

        $this->assertTrue($cast[0]['name'] === 'Benedict Cumberbatch');
        $this->assertTrue($cast[0]['character'] === 'Dr. Stephen Strange / Sinister Strange / Defender Strange');
    }

    public function test_find_matching_movie()
    {
        Http::preventStrayRequests();

        Http::fake([
            TheMovieDatabase::BASE_URL . 'search/movie?query=Doctor%20Strange%20in%20the%20Multiverse%20of%20Madness&api_key=1111' => $this->get_mock_response('movie.json'),
            TheMovieDatabase::BASE_URL . 'movie/453395/credits?api_key=1111' => $this->get_mock_response('movie_cast.json')

        ]);

        $client = new TheMovieDatabase();

        $movie = $client->findMatchingMovie('Doctor Strange in the Multiverse of Madness');

        Http::assertSentCount(2);

        $this->assertTrue($movie->original_title === 'Doctor Strange in the Multiverse of Madness');
        $this->assertTrue($movie->poster_path === '/9Gtg2DzBhmYamXBS1hKAhiwbBKS.jpg');
        $this->assertTrue($movie->backdrop_path === '/wcKFYIiVDvRURrzglV9kGu7fpfY.jpg');
        $this->assertTrue($movie->cast instanceof \Illuminate\Support\Collection);
    }

    public function test_movie_doesnt_exist()
    {
        Http::preventStrayRequests();

        Http::fake([
            TheMovieDatabase::BASE_URL . 'search/movie?query=thismoviedoesntexist&api_key=1111' => $this->get_mock_response('movie_doesnt_exist.json')
        ]);

        $client = new TheMovieDatabase();

        $this->expectException(\Exception::class);
        $movie = $client->findMatchingMovie('thismoviedoesntexist');

        Http::assertSentCount(1);
    }

    private function get_mock_response(string $filename)
    {
        return Http::response(file_get_contents(base_path('tests/Datasets/' . $filename)), 200, ['Headers']);
    }
}
