<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class CategorypostController extends Controller
{
    public function index($category)
    {
        $ArticlesbyCategory = Article::where('category_name', $category)
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('categorypost', compact('ArticlesbyCategory', 'category'));
    }
}