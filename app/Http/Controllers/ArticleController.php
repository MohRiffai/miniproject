<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->keywords;
        $inline = 3;
        if (strlen($keywords)) {
            $data = article::where('id', 'like', "%$keywords%")
                ->orWhere('name', 'like', "%$keywords%")
                ->orWhere('description', 'like', "%$keywords%")
                ->paginate($inline);
        } else {
            $data = article::orderBy('id', 'desc')->paginate($inline);
        }
        return view(view: 'articles.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];
        article::create($data);
        return redirect()->to('articles')->with('success', 'Successfully added data');
    }

    /**
     * Display the specified resource.
     */
    public function show(article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(article $article)
    {
        $data = article::find($article->id); // Use $article->id to access the article's ID
        return view('articles.edit')->with('data', $data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, article $article)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required', // You can add email validation here
        ]);
        // Update article data
        $article->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('articles.index')->with('success', 'Successfully updated data');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(article $article)
    {
        $article->delete(); // Use the $article instance to delete the article.

        return redirect()->to('articles')->with('success', 'Successfully deleted data');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(article::class, 'slug', $request->tittle);
        return response()->json(['slug' => $slug]);
    }
}
