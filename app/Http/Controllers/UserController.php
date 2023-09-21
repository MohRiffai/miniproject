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
        $inline = 3;
        if (strlen($keywords)) {
            $data = user::where('id', 'like', "%$keywords%")
                ->orWhere('name', 'like', "%$keywords%")
                ->orWhere('email', 'like', "%$keywords%")
                ->orWhere('phone', 'like', "%$keywords%")
                ->paginate($inline);
        } else {
            $data = user::orderBy('id', 'desc')->paginate($inline);
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
    public function store(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if($request->phone == ""){
            $phone = $user->phone;
        }else{
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
            'email' => 'required|email', // You can add email validation here
            // 'password' => 'min:8', // Add password validation rules here
            'phone' => 'required',

        ]);

        if($request->password == ""){
            $password = $user->password;
        }else{
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
