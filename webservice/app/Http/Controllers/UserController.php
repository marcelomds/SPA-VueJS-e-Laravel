<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validate->fails()) {
            return $validate->errors();
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = \auth()->user();
            $user->token = $user->createToken($user->email)->accessToken;

            return $user;
        } else {
            return ['status' => false];
        }

    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validate->fails()) {
            return $validate->errors();
        }

        $user = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->token = $user->createToken($user->email)->accessToken;

        return $user;
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $data = $request->all();

        if (isset($data['password'])) {
            $validate = Validator::make($data, [
                'name' => 'required|string',
                'email' => ['required', 'string', 'email', Rule::unique('users')->ignore($user->id)],
                'password' => 'required|string|min:6|confirmed'
            ]);

            if ($validate->fails()) {
                return $validate->errors();
            }

            $user->password = bcrypt($data['password']);
        } else {
            $validate = Validator::make($data, [
                'name' => 'required|string',
                'email' => ['required', 'string', 'email', Rule::unique('users')->ignore($user->id)],
            ]);

            if ($validate->fails()) {
                return $validate->errors();
            }

            $user->name = $data['name'];
            $user->email = $data['email'];

        }

        $user->save();

        $user->token = $user->createToken($user->email)->accessToken;

        return $user;
    }
}
