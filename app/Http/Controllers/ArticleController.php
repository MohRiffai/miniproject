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
        $data= $request->validate([
            'tittle' => 'required|max:255',
            'slug' => 'required|unique:articles',
            'category_id' => 'required',
            'content' => 'required',
        ]);
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
        $data = article::find($article->id); 
        return view('articles.edit',[
            'categories' => Category::all()
        ])->with('data', $data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
{
    $request->validate([
        'tittle' => 'required|max:255',
        'slug' => 'required|unique:articles,slug,' . $article->id,
        'category_id' => 'required',
        'content' => 'required',
    ]);

    $article->update([
        'tittle' => $request->input('tittle'),
        'slug' => $request->input('slug'),
        'category_id' => $request->input('category_id'),
        'content' => $request->input('content'),
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
