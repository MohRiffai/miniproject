<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class AuthorpostController extends Controller
{
    public function index($author)
    {
        // Ambil artikel yang terkait dengan penulis yang dipilih
        $ArticlesbyAuthor = Article::whereHas('author', function ($query) use ($author) {
            $query->where('name', $author);
        })->orderBy('created_at', 'desc')->paginate(6);

        return view('authorpost', compact('ArticlesbyAuthor', 'author'));
    }
}