<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;

class UserController extends Controller
{
    public function store(CreateUserRequest $request)
    {
        $user = $request->validated();
        $user['password'] = Hash::make($request->validated()['password']);
        // dd(Hash::make($request->validated()['password']));

        User::create($user);

    }
}
