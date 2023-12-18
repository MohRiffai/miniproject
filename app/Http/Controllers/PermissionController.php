<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class permissionController extends Controller
{
    public function index(Request $request, Permission $permission)
    {
        $this->authorize('viewAny', $permission);
        $keywords = $request->keywords;
        $inline = 10;
        if (strlen($keywords)) {
            $data = permission::where('id', 'like', "%$keywords%")
                ->orWhere('name', 'like', "%$keywords%")
                ->orWhere('guard_name', 'like', "%$keywords%")
                ->paginate($inline);
        } else {
            $data = permission::orderBy('id', 'desc')->paginate($inline);
        }
        return view(view: 'permissions.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Permission $permission)
    {
        $this->authorize('create', $permission);
        return view(view: 'permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $data = [
            'name' => $request->name,
        ];
        permission::create($data);
        return redirect()->to('permissions')->with('success', 'Successfully added data');
    }

    /**
     * Display the specified resource.
     */
    public function show(permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(permission $permission)
    {
        $this->authorize('update', $permission);
        $data = permission::find($permission->id); // Use $permission->id to access the permission's ID
        return view('permissions.edit')->with('data', $data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, permission $permission)
    {
        $this->authorize('update', $permission);
        $request->validate([
            'name' => 'required',
        ]);
        // Update permission data
        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Successfully updated data');

        // $validated = $request->validate(['name' => ['required']]);
        // $permission->update($validated);

        // return to_route('permissions.index')->with('success', 'Successfully updated data');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(permission $permission)
    {
        $this->authorize('destory', $permission);
        $permission->delete(); // Use the $permission instance to delete the permission.

        return redirect()->to('permissions')->with('success', 'Successfully deleted data');
    }
}
