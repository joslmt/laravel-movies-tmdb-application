<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchMoviesRequest;
use App\Http\Requests\UpdatedUserDescriptionRequest;
use App\Models\Movie;
use App\Services\Category\Category;
use App\Services\Paginator\CustomPaginator;
use Illuminate\Contracts\View\View;

class MoviesController extends Controller
{
    private const MOVIES_PER_PAGE = 12;
    
    public function __construct(
        private CustomPaginator $paginator,
        private Movie $movie
    ){
    }

    public function home(Category $category): View
    {
        $userFavMovies = $this->movie->favouriteMovies();

        $movies = $this->movie->topMovies();

        $category->setCategoryToMovie($movies);

        $paginator = $this->paginator->create(
            items: $movies,
            total: count($movies),
            perPage: self::MOVIES_PER_PAGE,
        );
        
        $paginatedMovies = $this->paginator->paginate(
            dataToPaginate: $movies,
            offSet: self::MOVIES_PER_PAGE,
            itemsPerPage: self::MOVIES_PER_PAGE
        );

        return view('dashboard', compact('paginatedMovies', 'paginator', 'userFavMovies'));
    }

    public function movieDetails(int $id): View
    {
        $userFavMovies = $this->movie->favouriteMovies();
        $movie_details = $this->movie->movieDetails($id);
        $videos = $movie_details->videos->results;

        return view('components.movieweb.movies.moviedetails', compact('movie_details', 'videos', 'userFavMovies'));
    }

    public function profile(Movie $movie): View
    {
        $favMovies = $movie->getProfileInfo();
        $description = $movie->profileUserDescription();
        return view('components.movieweb.general.profile', compact('favMovies', 'description'));
    }

    public function description(UpdatedUserDescriptionRequest $request)
    {
        $this->movie->updateDescription($request->input('description'));
        return redirect()->back();
    }

    public function favouriteMovies(Movie $movie): array
    {
        return $movie->favouriteMovies();
    }

    public function saveMovie(int $id, string $title, string $poster_path)
    {
        $this->movie->saveMovie($id, $title, $poster_path);
        return redirect()->back();
    }

    public function searchMovie(SearchMoviesRequest $request, Movie  $movie, Category $category): View
    {
        $userFavMovies = $movie->favouriteMovies();

        $movies = $movie->searchMovie($request->input('movie'));
        
        $category->setCategoryToMovie($movies);

        $paginator = $this->paginator->create(
            items: $movies,
            total: count($movies),
            perPage: self::MOVIES_PER_PAGE,
            options: [ 'query' => $request->query() ]
        );
        
        $paginatedMovies = $this->paginator->paginate(
            dataToPaginate: $movies,
            offSet: self::MOVIES_PER_PAGE,
            itemsPerPage: self::MOVIES_PER_PAGE
        );

        return view('components.movieweb.general.resultsfound', compact('paginatedMovies', 'paginator', 'userFavMovies'));
    }

    public function removeMovie(int $movie_id)
    {
        $this->movie->removeMovie($movie_id);
        return redirect()->back();
    }
}
