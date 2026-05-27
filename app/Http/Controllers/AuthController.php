<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function Login(Request $request)
    {

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Không được để trống ',
            'password.required' => 'không được để trống',
        ]);
        $username = $request->input('username');
        $password = $request->input('password');
        $user = Users::where('studentid', $username)->orWhere('email', $username)->first();
        if ($user && md5($password) === $user->password) {
            Auth::login($user);
            $request->session()->regenerate();
            if ($user->gid == 0) {
                return redirect('/admin');
            }
            return redirect()->intended('/');
        }

        if (!$user) {
            return back()->withErrors(['login_error' => 'Tài khoản không chính xác']);
        }

        return back()->withErrors(['login_error' => 'Mật khẩu không chính xác']);
    }
    public function Logout()
    {
        session()->flush();
        return redirect('/');
    }
}
