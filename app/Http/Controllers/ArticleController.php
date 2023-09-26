<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
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
                ->orWhere('tittle', 'like', "%$keywords%")
                ->orWhere('category_name', 'like', "%$keywords%")
                ->orWhere('slug', 'like', "%$keywords%")
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
        ], ['tags' => Tag::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tittle' => 'required|max:255',
            'slug' => 'required|unique:articles',
            'category_name' => 'required',
            'content' => 'required',
            'tag_id' => 'required|array',
        ]);

        $setTagIdAsString = implode(',', $data['tag_id']);

        // Modify the 'tag_id' value in the $data array
        $data['tag_id'] = $setTagIdAsString;

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
    public function edit(Article $article)
    {
        // Find the article by ID
        $data = Article::find($article->id);

        // Retrieve the selected tag IDs for the article
        $selectedTagIds = explode(',', $data->tag_id);

        return view('articles.edit', [
            'article' => $article,
            'categories' => Category::all(),
            'tags' => Tag::all(),
            'selectedTagIds' => $selectedTagIds, // Pass the selected tag IDs to the view
            'data' => $data, // Pass the $data variable to the view
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'tittle' => 'required|max:255',
            'slug' => 'required|unique:articles,slug,' . $article->id,
            'category_name' => 'required',
            'content' => 'required',
            'tag_id' => 'required|array',
        ]);

        $setTagIdAsString = implode(',', $request->input('tag_id'));

        $article->update([
            'tittle' => $request->input('tittle'),
            'slug' => $request->input('slug'),
            'category_name' => $request->input('category_name'),
            'content' => $request->input('content'),
            'tag_id' => $setTagIdAsString,
        ]);

        return redirect()->route('articles.index')->with('success', 'Successfully updated data');
    }



    /**
     * Remove the specified resource from storage.
     */

    // public function destroy(article $article)
    // {
    //     $article->delete(); // Use the $article instance to delete the article.

    //     return redirect()->to('articles')->with('success', 'Successfully deleted data');
    // }
    public function destroy($slug)
    {
        $article = Article::where('slug', $slug)->first();

        if (!$article) {
            return redirect()->to('articles')->with('error', 'Article not found');
        }

        $article->delete();

        return redirect()->to('articles')->with('success', 'Successfully deleted data');
    }


    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(article::class, 'slug', $request->tittle);
        return response()->json(['slug' => $slug]);
    }
}
