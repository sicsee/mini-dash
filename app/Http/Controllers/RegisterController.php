<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function store(RegisterRequest $request)
    {
        $user = User::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        Auth::login($user);

        return redirect()->route('site.dashboard');
    }
}
