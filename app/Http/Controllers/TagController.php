<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keywords = $request->keywords;
        $inline = 3;
        if (strlen($keywords)) {
            $data = tag::where('id', 'like', "%$keywords%")
                ->orWhere('name', 'like', "%$keywords%")
                ->orWhere('description', 'like', "%$keywords%")
                ->paginate($inline);
        } else {
            $data = tag::orderBy('name', 'desc')->paginate($inline);
        }
        return view(view: 'tags.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(view: 'tags.create');
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
        tag::create($data);
        return redirect()->to('tags')->with('success', 'Successfully added data');
    }

    /**
     * Display the specified resource.
     */
    public function show(tag $tag)
    {
        //
    }

    public function edit(tag $tag)
    {
        $data = tag::find($tag->id); // Use $tag->id to access the tag's ID
        return view('tags.edit')->with('data', $data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tag $tag)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',

        ]);
        // Update tag data
        $tag->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('tags.index')->with('success', 'Successfully updated data');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(tag $tag)
    {
        $tag->delete(); // Use the $tag instance to delete the tag.

        return redirect()->to('tags')->with('success', 'Successfully deleted data');
    }
}
