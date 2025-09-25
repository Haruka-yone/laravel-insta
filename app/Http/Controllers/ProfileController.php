<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function show($id){
        $user = $this->user->findOrFail($id);
        return view('users.profile.show')->with('user', $user);
    }

    public function edit(){
        $user = $this->user->findOrFail(Auth::user()->id);
        return view('users.profile.edit')->with('user', $user);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name'         => 'required|max:50',
            'email'        => 'required|max:50',
            'intoduction'  => 'max:100',
            'avatar'       => 'mimes:jpg,jpeg,png,gif|max:1048',
            'current_password' => 'nullable|required_with:password|string',
            'password'      => 'nullable|min:8|confirmed'

        ]);

        $user = $this->user->findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;
        
        if($request->avatar){
            $user->avatar ='data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect'])->withInput();
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.show', $id);
    }

    public function followers($id){
        $user = $this->user->findOrFail($id);
        
        return view('users.profile.followers')->with('user', $user);

    }

    public function following($id){
        $user = $this->user->findOrFail($id);

        return view('users.profile.following')->with('user', $user);
    }

}
