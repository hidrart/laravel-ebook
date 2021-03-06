<?php

namespace App\Http\Controllers;

use App\Models\User;
use PharIo\Manifest\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Egulias\EmailValidator\Result\Result;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('admin.index', [
            "users" => User::oldest()->filter(request(['username', 'role']))->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('user.index', [
            "user" => Auth::user(),
            "orders" => Auth::user()->order,
        ]);
    }

    public function detail() 
    {
        return view('user.edit', [
            "user" => Auth::user()
        ]);
    }

    public function complete(Request $request) 
    {
        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', Password::defaults()],
            'image' => ['image', 'file', 'max:2048']
        ]);

        if($request->file('image')) {
            if($request->before) {
                Storage::delete($request->before);
            }
            $validated['image'] = $request->file('image')->store('image');
        }

        $validated['password'] = bcrypt($validated['password']);

        $request->user()->update($validated);
        return redirect()->route('profile')->with('success', 'User successfully updated');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.show', [
            "user" => $user,
            "orders" => $user->order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.edit', [
            "user" => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {   

        $validated = $request->validate([
            'firstname' => ['max:255'],
            'middlename' => ['max:255'],
            'lastname' => ['max:255'],
            'gender' => ['max:255'],
            'username' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string'],
            'image' => ['image', 'file', 'max:2048']
        ]);

        if($request->file('image')) {
            if($request->before) {
                Storage::delete($request->before);
            }
            $validated['image'] = $request->file('image')->store('image');
        }

        $user->update($validated);
        return redirect("/admin")->with('success', 'User successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->image) {
            Storage::delete($user->image);
        }
        $user->delete();
        return redirect('/admin')->with('success', 'User successfully deleted!');
    }
}
