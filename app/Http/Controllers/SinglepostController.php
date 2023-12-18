<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;

class SinglepostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index($slug)
    {
        $post = Article::where('slug', $slug)->first(); // Menggunakan model Article untuk mencari post

        // Mengambil artikel terbaru
        $latestArticles = Article::where('slug', '!=', $slug) // Menghindari artikel yang sedang ditampilkan
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // $selectedTagIds = explode(',', $post->tag_name);

        // Jika post ditemukan, tampilkan view single post
        if ($post) {
            return view('singlepost', [
                // 'categories' => Category::all(),
                'tags' => Tag::all(),
                // 'authors' => User::all(),
                // 'selectedTagIds' => $selectedTagIds,
                'latestArticles' => $latestArticles,
                'post' => $post
            ]);
        }

        // Jika post tidak ditemukan, lakukan penanganan error, seperti menampilkan halaman 404
        abort(404);
    }
}
