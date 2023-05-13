<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\MovieService;
use App\ViewModels\MoviesViewModel;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function __construct(private readonly MovieService $service)
    {
    }

    public function index(Request $request)
    {
        $perPage = $this->getPerPageValue($request, 'movies');

        $viewModel = new MoviesViewModel(
            $this->service->getMovies($perPage)
        );

        return view('movies.index', $viewModel);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(Movie $movie)
    {
    }

    public function edit(Movie $movie)
    {
    }

    public function update(Request $request, Movie $movie)
    {
    }

    public function destroy(Movie $movie)
    {
    }
}
