<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $latestArticlesSlider = Article::orderBy('created_at', 'desc')->take(5)->get();

        $latestArticles= Article::orderBy('created_at', 'desc')->paginate(6);

        return view('home', compact('latestArticles', 'latestArticlesSlider'));
    }
}
