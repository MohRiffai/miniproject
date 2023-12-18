<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class TagpostController extends Controller
{
    public function index($tag)
    {
        $ArticlesbyTag = Article::where('tag_name', 'LIKE', "%$tag%")
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('tagpost', compact('ArticlesbyTag', 'tag'));
    }
}