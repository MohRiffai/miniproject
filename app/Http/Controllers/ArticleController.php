<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(Request $request, Article $article)
    {
        // $this->authorize('admin');
        $keywords = $request->keywords;
        $inline = 10;
        $data = Article::query()->with('author');
        if (strlen($keywords)) {
            $data = article::where('id', 'like', "%$keywords%")
                ->orWhere('tittle', 'like', "%$keywords%")
                ->orWhere('category_name', 'like', "%$keywords%")
                ->orWhere('slug', 'like', "%$keywords%")
                ->orWhere('tag_name', 'like', "%$keywords%")
                ->orWhereHas('author', function($query) use ($keywords){
                    $query->where('name', 'like', "%$keywords%");
                });
                // ->paginate($inline);
        } 
        else {
            $data = article::orderBy('id', 'desc');
        }

        $data = $data->paginate($inline);

        return view('articles.index', [
            'article' => $article,
            'tags' => Tag::all(),
            'data' => $data,
        ]);
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
            'image' => 'image|file|max:1024',
            'content' => 'required',
            'tag_name' => 'required|array',
        ]);

        $data = $request->all();
        $data['author_id'] = auth()->user()->id;

        // Mengunggah gambar dan mendapatkan path-nya
        if ($request->file('image')) {
            $imagePath = $request->file('image')->store('post-images');
            $data['image'] = $imagePath; // Menyimpan path gambar ke dalam data artikel
        }

        $setTagIdAsString = implode(',', $data['tag_name']);

        // Modify the 'tag_id' value in the $data array
        $data['tag_name'] = $setTagIdAsString;

        // Menambahkan kolom "Author" dengan nama pengguna pengguna yang sedang login
        // $user = Auth::user(); // Mendapatkan pengguna yang sedang login
        // $data['author'] = $user->name; // Mengisi kolom "Author" dengan nama pengguna


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
        $this->authorize('update', $article);
        // Find the article by ID
        $data = Article::find($article->id);

        // Retrieve the selected tag IDs for the article
        $selectedTagIds = explode(',', $data->tag_name);

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
        $this->authorize('update', $article);
        $request->validate([
            'tittle' => 'required|max:255',
            'slug' => 'required|unique:articles,slug,' . $article->id,
            'category_name' => 'required',
            'image' => 'image|file|max:1024',
            'content' => 'required',
            'tag_name' => 'required|array',
        ]);

        // $user = auth()->user();
        // $author = $user->name;

        $imagePath = $article->image;

        if ($request->file('image')) {
            if ($article->image) {
                Storage::delete($article->image);
            }

            $imagePath = $request->file('image')->store('post-images');
            $data['image'] = $imagePath; // Menyimpan path gambar ke dalam data artikel
        }

        // $this->authorize('update', $article);

        $setTagIdAsString = implode(',', $request->input('tag_name'));

        $article->update([
            'tittle' => $request->input('tittle'),
            'slug' => $request->input('slug'),
            // 'author' => $author, 
            'category_name' => $request->input('category_name'),
            'content' => $request->input('content'),
            'tag_name' => $setTagIdAsString,
            'image' => $imagePath,
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

        $this->authorize('delete', $article);

        if ($article->image){
            Storage::delete($article->image);
        }

        // $this->authorize('delete', $article);

        $article->delete();

        return redirect()->to('articles')->with('success', 'Successfully deleted data');
    }


    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(article::class, 'slug', $request->tittle);
        return response()->json(['slug' => $slug]);
    }
}
