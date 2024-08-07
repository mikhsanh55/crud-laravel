<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        // display user list
        return view('user', [
            'users' => User::all()
        ]);
    }

    /**
     * Display add form
     * 
     */
    public function add()
    {
        return view('add-user');
    }

    /**
     * Insert new user
     * 
     */
    public function store()
    {
        $user = new User();
        $user->name = request('name');
        $user->email = request('email');
        $user->save();

        return redirect()->intended('/user');
    }

    /**
     * Display edit form
     * 
     */
    public function edit()
    {
        $userId = request('id');

        $user = User::where('id', $userId)->first();
        // dd($user->toArray());

        return view('edit-user', [
            'user' => $user
        ]);
    }

    /**
     * Update user
     * 
     */
    public function update()
    {
        $userId = request('id');
        $name = request('name');
        $email = request('email');

        $user = User::where('id', $userId)->first();
        $user->name = $name;
        $user->email = $email;
        $user->save();

        return redirect()->intended('/user');
        
    }

    /**
     * Delete user
     * 
     */
    public function delete()
    {
        $userId = request('id');
        $user = User::where('id', $userId)->first();
        $user->delete();

        return redirect()->intended('/user');
    }
}
