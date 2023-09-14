<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keywords = $request->keywords;
        $inline =3;
        if(strlen($keywords)){
            $data = user::where('id','like',"%$keywords%")
                ->orWhere('name','like',"%$keywords%")
                ->orWhere('email','like',"%$keywords%")
                ->paginate($inline);
        }else{
        $data = user::orderBy('name', 'desc')->paginate($inline);
        }
        return view(view: 'users.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(view: 'users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
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
    public function edit(User $user)
    {
        $data = user::where('id', $user)->first();
        return view('users.edit')->with('data', $data);
        // return 'HI '. $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required',
        //     'password' => 'required',
        //     'role' => 'required',
        // ]);
        // $data = [
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => $request->password,
        //     'role' => $request->role,
        // ];
        // User::where('id', $user)->update($data);
        // return redirect()->to('users')->with('success', 'Successfully update data');
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
