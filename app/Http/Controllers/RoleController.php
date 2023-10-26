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

        $roleHasPermissions = DB::table('role_has_permissions')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
            ->select('roles.name as role_name', 'permissions.name as permission_name')
            ->get();


        return view('roles.manage-role', [
            'data' => $role,
            'permissions' => $permissions,
            'roles' => $roles,
            'models' => $models,
            'modelHasRoles' => $modelHasRoles,
            'roleHasPermissions' => $roleHasPermissions,
        ]);
    }

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

    public function givePermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|integer', // Validasi role_id
            'permission_id' => 'required|integer', // Validasi permission_id
        ]);

        $role = Role::findById($request->role_id);

        if (!$role) {
            return back()->with('error', 'Role not found.');
        }

        $permission = Permission::findById($request->permission_id);

        if ($permission) {
            if ($role->hasPermissionTo($permission->name)) {
                return back()->with('message', 'Permission already exists.');
            }

            $role->givePermissionTo($permission);

            return back()->with('message', 'Permission added successfully.');
        } else {
            return back()->with('error', 'Permission not found.');
        }
    }

    public function revokePermission($role_name, $permission_name)
    {
        $role = Role::where('name', $role_name)->first();
        $permission = Permission::where('name', $permission_name)->first();

        if ($role && $permission) {
            if ($role->hasPermissionTo($permission)) {
                $role->revokePermissionTo($permission);
                return redirect()->back()->with('success', 'Permission revoked successfully');
            }
        }

        return redirect()->back()->with('error', 'Permission not found or not assigned to the role.');
    }
}
