<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->keywords;
        $inline = 3;
        if (strlen($keywords)) {
            $data = category::where('id', 'like', "%$keywords%")
                ->orWhere('name', 'like', "%$keywords%")
                ->orWhere('description', 'like', "%$keywords%")
                ->paginate($inline);
        } else {
            $data = category::orderBy('id', 'desc')->paginate($inline);
        }
        return view(view: 'categories.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(view: 'categories.create');
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
        Category::create($data);
        return redirect()->to('categories')->with('success', 'Successfully added data');
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category $category)
    {
        $data = category::find($category->id); // Use $category->id to access the category's ID
        return view('categories.edit')->with('data', $data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required', // You can add email validation here
        ]);
        // Update category data
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Successfully updated data');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(category $category)
    {
        $category->delete(); // Use the $category instance to delete the category.

        return redirect()->to('categories')->with('success', 'Successfully deleted data');
    }
}
