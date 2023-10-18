<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class RoleController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->keywords;
        $inline = 3;
        if (strlen($keywords)) {
            $data = role::where('id', 'like', "%$keywords%")
                ->orWhere('name', 'like', "%$keywords%")
                ->orWhere('guard_name', 'like', "%$keywords%")
                ->paginate($inline);
        } else {
            $data = role::orderBy('id', 'desc')->paginate($inline);
        }
        return view(view: 'roles.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(view: 'roles.create');
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
        Role::create($data);
        return redirect()->to('roles')->with('success', 'Successfully added data');
    }

    /**
     * Display the specified resource.
     */
    public function show(role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $data = role::find($role->id); // Use $role->id to access the role's ID
        return view('roles.edit', [
            'permissions' => Permission::all(),
        ])->with('data', $data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required',
        ]);
        // Update role data
        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index')->with('success', 'Successfully updated data');

        // $validated = $request->validate(['name' => ['required']]);
        // $role->update($validated);

        // return to_route('roles.index')->with('success', 'Successfully updated data');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Role $role)
    {
        $role->delete(); // Use the $role instance to delete the role.

        return redirect()->to('roles')->with('success', 'Successfully deleted data');
    }

    // public function manage(Role $role)
    // {
    //     $models = User::all();
    //     $roles = Role::all();
    //     $permissions = Permission::all(); // Add this line to get the permissions
    //     $modelHasRoles = $role->model_has_roles;
    //     return view('roles.manage-role', [
    //         'data' => $role,
    //         'permissions' => $permissions, // Pass the 'permissions' variable to the view
    //         'roles' => $roles,
    //         'models' => $models,

    //     ]);
    // }

    public function manage(Role $role)
    {
        $models = User::all();
        $roles = Role::all();
        $permissions = Permission::all();

        $modelHasRoles = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->select('users.name as user_name', 'roles.name as role_name')
            ->where('model_has_roles.model_type', 'App\Models\User')
            ->get();


        return view('roles.manage-role', [
            'data' => $role,
            'permissions' => $permissions,
            'roles' => $roles,
            'models' => $models,
            'modelHasRoles' => $modelHasRoles,
        ]);
    }

    public function updatePermission(Request $request, Role $role)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $permission = Permission::findOrFail($request->input('permission_id'));
        $role->givePermissionTo($permission);

        return redirect()->route('roles.manage', $role)->with('success', 'Permission added successfully.');
    }

    // public function assignRole(Request $request, Role $role)
    // {
    //     $request->validate([
    //         'user_id' => 'required|integer',
    //         'role_id' => 'required|integer',
    //     ]);

    //     try {
    //         $user = User::findOrFail($request->user_id);
    //         $role = Role::findOrFail($request->role_id);

    //         $user->assignRole($role, 'web');

    //         // You can optionally attach permissions here as well if needed
    //         // Example: $user->givePermissionTo('permission-name');

    //         return redirect()->route('roles.index')->with('success', 'Role assigned successfully');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Role assignment failed: ' . $e->getMessage());
    //     }
    // }
    public function assignRole(Request $request)
    {
        $request->validate([
            'model_id' => 'required|integer',
            'role_id' => 'required|integer',
        ]);

        $model = User::findOrFail($request->model_id);
        $role = Role::findOrFail($request->role_id);

        $model->assignRole($role);

        return redirect()->route('roles.manage')->with('success', 'Role assigned successfully');
    }

    // public function removeRole($user_id, $role_id)
    // {
    //     $user = User::findOrFail($user_id);
    //     $role = Role::findOrFail($role_id);

    //     $user->removeRole($role);

    //     return redirect()->back()->with('success', 'Role removed successfully');
    // }


    // public function removeRole($model_id, $role_id)
    // {
    //     DB::table('model_has_roles')
    //         ->where('model_id', $model_id)
    //         ->where('role_id', $role_id)
    //         ->delete();

    //     return redirect()->back()->with('success', 'Role removed successfully');
    // }

    public function removeRole($user_name, $role_name)
    {
        $user = User::where('name', $user_name)->first(); // Ganti 'name' dengan kolom yang sesuai pada tabel User
        $role = Role::where('name', $role_name)->first(); // Ganti 'name' dengan kolom yang sesuai pada tabel Role

        if ($user && $role) {
            if ($user->hasRole($role)) {
                $user->removeRole($role);
                return redirect()->back()->with('success', 'Role removed successfully');
            }
        }

        return redirect()->back()->with('error', 'Role not found or not assigned to the user.');
    }



    // public function revokeRole($user_name, $role_name)
    // {
    //     // Find the user and role by name or any other criteria that you use
    //     $user = User::where('name', $user_name)->first();
    //     $role = Role::where('name', $role_name)->first();

    //     if ($user && $role) {
    //         // Use the revokeRoleTo method to revoke the role from the user
    //         $user->revokeRoleTo($role);

    //         return redirect()->back()->with('success', 'Role revoked successfully');
    //     }

    //     return redirect()->back()->with('error', 'User or role not found');
    // }




    // public function assignRole(Request $request)
    // {
    //     $user = User::findOrFail($request->user_id);

    //     if ($user->hasRole($request->role_id)) {
    //         return redirect()->route('roles.index')->with('message', 'Role exists.');
    //     }

    //     $user->assignRole($request->role_id);

    //     return redirect()->route('roles.index')->with('message', 'Role assigned.');
    // }
}
