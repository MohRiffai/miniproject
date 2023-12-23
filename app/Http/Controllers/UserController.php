<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {

    //     $keywords = $request->keywords;
    //     $inline = 5;
    //     if (strlen($keywords)) {
    //         $data = user::where('id', 'like', "%$keywords%")
    //             ->orWhere('name', 'like', "%$keywords%")
    //             ->orWhere('email', 'like', "%$keywords%")
    //             ->orWhere('phone', 'like', "%$keywords%")
    //             ->paginate($inline);
    //     } else {
    //         $data = user::orderBy('id', 'desc')->paginate($inline);
    //     }
    //     $modelHasRoles = DB::table('model_has_roles')
    //         ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
    //         ->join('users', 'model_has_roles.model_id', '=', 'users.id')
    //         ->select('users.name as user_name', 'roles.name as role_name')
    //         ->where('model_has_roles.model_type', 'App\Models\User')
    //         ->get();
    //     return view('users.index', [
    //             'roles' => Role::all(),
    //             'modelHasRoles' => $modelHasRoles,
    //             'data'=> $data    
    //     ]);
    // }

    // public function index(Request $request)
    // {
    //     $keywords = $request->keywords;
    //     $inline = 5;

    //     $data = User::query();

    //     if (strlen($keywords)) {
    //         $data->where('id', 'like', "%$keywords%")
    //             ->orWhere('name', 'like', "%$keywords%")
    //             ->orWhere('email', 'like', "%$keywords%")
    //             ->orWhere('phone', 'like', "%$keywords%");

    //         // Tambahkan juga pencarian berdasarkan role
    //             $data->orWhereHas('roles', function ($query) use ($keywords) {
    //             $query->where('name', 'like', "%$keywords%");
    //         });
    //     } else {
    //         $data = user::orderBy('id', 'desc');
    //     }

    //     $data = $data->paginate($inline);

    //     $modelHasRoles = DB::table('model_has_roles')
    //         ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
    //         ->join('users', 'model_has_roles.model_id', '=', 'users.id')
    //         ->select('users.name as user_name', 'roles.name as role_name')
    //         ->where('model_has_roles.model_type', 'App\Models\User')
    //         ->get();

    //     return view('users.index', [
    //         'roles' => Role::all(),
    //         'modelHasRoles' => $modelHasRoles,
    //         'data' => $data
    //     ]);
    // }

    public function index(Request $request)
    {
        $keywords = $request->keywords;
        $roleFilter = $request->role; // Filter berdasarkan peran

        $inline = 5;

        $currentUser = Auth::user(); // Mendapatkan pengguna saat ini

        $data = User::query();

        if (!$currentUser->hasRole('Admin')) {
            // Jika pengguna bukan admin, filter data yang ditampilkan
            $data->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Admin');
            });
        }

        if (strlen($keywords)) {
            // Lakukan pencarian berdasarkan kata kunci
            $data->where(function ($query) use ($keywords) {
                $query->where('id', 'like', "%$keywords%")
                    ->orWhere('name', 'like', "%$keywords%")
                    ->orWhere('email', 'like', "%$keywords%")
                    ->orWhere('phone', 'like', "%$keywords%")
                    ->orWhereHas('roles', function ($roleQuery) use ($keywords) {
                        $roleQuery->where('name', 'like', "%$keywords%");
                    });
            });
        }

        $data = $data->orderBy('id', 'desc')->paginate($inline);

        // Ambil informasi model_has_roles hanya jika pengguna adalah admin
        $modelHasRoles = [];
        if ($currentUser->hasRole('admin')) {
            $modelHasRoles = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                ->select('users.name as user_name', 'roles.name as role_name')
                ->where('model_has_roles.model_type', 'App\Models\User')
                ->get();
        }

        return view('users.index', [
            'roles' => Role::all(),
            'data' => $data,
            'modelHasRoles' => $modelHasRoles,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create', [
            'roles' => Role::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($request->phone == "") {
            $phone = $user->phone;
        } else {
            $phone = $request->phone;
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password)
        ];

        User::create($data);

        return redirect()->to('users')->with('success', 'Successfully added data');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(User $user)
    // {
    //     $data = user::where('id', $user)->first();
    //     return view('users.edit')->with('data', $data);
    // }

    public function edit(User $user)
    {
        $data = User::find($user->id); // Use $user->id to access the user's ID
        return view('users.edit')->with('data', $data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email', 
            'phone' => 'required',

        ]);

        if ($request->password == "") {
            $password = $user->password;
        } else {
            $password = bcrypt($request->password);
        }

        // dd($user->id);
        // Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $password, // Hash the password before updating
        ]);

        return redirect()->route('users.index')->with('success', 'Successfully updated data');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(User $user)
    {
        $user->delete(); // Use the $user instance to delete the user.

        return redirect()->to('users')->with('success', 'Successfully deleted data');
    }
}
